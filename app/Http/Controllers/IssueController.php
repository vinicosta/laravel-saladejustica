<?php

namespace App\Http\Controllers;

use DB;
use App\Issue;
use App\Title;
use App\Periodicity;
use Illuminate\Http\Request;
use App\Http\Requests\IssueRequest;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Issue $model, string $type)
    {
        $issues = $this->getReadingList(typeId($type));

        return view("$type.index", ['issues' => $issues, 'result' => 'issues']);
    }

    public function getReadingList(int $type)
    {
        // Get reading titles
        $titles = Title::select('titles.id')
            ->join('reading', 'titles.id', '=', 'reading.title_id')
            ->where('titles.type_id', '=', $type)
            ->where('reading.user_id', '=', \Auth::id())
            ->get();

        $issues = [];

        // Loop over reading titles and checking if had unread issues
        foreach($titles as $title){
            $nextIssue = $this->getNextReading($title->id);
            if($nextIssue){
                $issues[] = $nextIssue;
            }
        }

        // Sort by issue publication date
        usort($issues, fn($a, $b) => strcmp($a->date_publication, $b->date_publication));

        return $issues;
    }

    public function getNextReading($title_id){

        // Check if title has readed issues
        $readeds = DB::select(
            "SELECT iss.id
            FROM issues iss
            INNER JOIN titles tit ON tit.id = iss.title_id
            INNER JOIN readed red ON iss.id = red.issue_id AND red.user_id = ?
            WHERE iss.title_id = ?",
            [\Auth::id(), $title_id]
        );

        if(!$readeds){

            // Title does not have readed issues, get first issue
            $issue = DB::select(
                "SELECT iss.id, iss.name, iss.issue_number, iss.image, iss.date_publication, pub.name AS publisher_name, '0' AS issue_count, col.id AS collection, '0' AS readed, tit.id AS title_id
                FROM issues iss
                INNER JOIN titles tit ON tit.id = iss.title_id
                LEFT JOIN publishers pub ON pub.id = tit.publisher_id
                LEFT JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
                WHERE iss.title_id = ?
                ORDER BY iss.date_publication, iss.issue_number
                LIMIT 1",
                [\Auth::id(), $title_id]
            );

        }
        else{

            // Title does have readed issues, get firts issue after last readed
            $issue = DB::select(
                "SELECT iss.id, iss.name, iss.issue_number, iss.image, iss.date_publication, pub.name AS publisher_name, '0' AS issue_count, col.id AS collection, '0' AS readed, tit.id AS title_id
                FROM issues iss
                INNER JOIN titles tit ON tit.id = iss.title_id
                LEFT JOIN publishers pub ON pub.id = tit.publisher_id
                LEFT JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
                WHERE iss.title_id = ?
                AND iss.id NOT IN (SELECT issues.id FROM issues INNER JOIN readed ON issues.id = readed.issue_id WHERE issues.title_id = ? AND readed.user_id = ?)
                AND iss.date_publication >= (SELECT issues.date_publication FROM issues INNER JOIN readed ON issues.id = readed.issue_id WHERE issues.title_id = ? AND readed.user_id = ? ORDER BY issues.date_publication DESC, issues.issue_number DESC LIMIT 1)
                ORDER BY iss.date_publication, iss.issue_number
                LIMIT 1",
                [\Auth::id(), $title_id, $title_id, \Auth::id(), $title_id, \Auth::id()]
            );

        }

        if($issue){
            return $issue[0];
        }
        else{
            return null;
        }
    }

    public function show(Issue $model, string $type, int $id)
    {
        $issue = DB::select(
            "SELECT iss.id, iss.name, iss.subtitle, iss.issue_number, iss.image, iss.date_publication, iss.title_id, iss.synopsis, pub.name AS publisher_name, 'issues' AS result, col.id AS collection, col.added_date, red.id AS readed, red.readed_date, sgr.name AS subgenre_name, siz.name AS size_name, tit.id AS title_id,
            (SELECT id FROM issues WHERE title_id = tit.id AND date_publication < iss.date_publication ORDER BY date_publication ASC LIMIT 1) AS first_issue,
            (SELECT id FROM issues WHERE title_id = tit.id AND date_publication < iss.date_publication ORDER BY date_publication DESC LIMIT 1) AS previous_issue,
            (SELECT id FROM issues WHERE title_id = tit.id AND date_publication > iss.date_publication ORDER BY date_publication ASC LIMIT 1) AS next_issue,
            (SELECT id FROM issues WHERE title_id = tit.id AND date_publication > iss.date_publication ORDER BY date_publication DESC LIMIT 1) AS last_issue
            FROM issues iss
            INNER JOIN titles tit ON iss.title_id = tit.id
            LEFT JOIN publishers pub ON tit.publisher_id = pub.id
            LEFT JOIN subgenres sgr ON tit.subgenre_id = sgr.id
            LEFT JOIN sizes siz ON tit.size_id = siz.id
            LEFT JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
            LEFT JOIN readed red ON iss.id = red.issue_id AND red.user_id = ?
            WHERE iss.id = ?",
            [\Auth::id(), \Auth::id(), ($id)]
        );

        return view("$type.show", ['issue' => $issue[0], 'result' => 'issues']);
    }

    /**
     * Show the form for creating a new issue
     *
     * @return \Illuminate\View\View
     */
    public function create(Issue $issue, string $type)
    {
        return view("$type.create", compact('issue'));
    }

    /**
     * Show the form for creating a new issue from title
     *
     * @return \Illuminate\View\View
     */
    public function createFromTitle(Issue $issue, string $type, int $title_id)
    {
        // Get title
        $title = Title::find($title_id);

        // Get last issue
        $issue = Issue::select('*')
            ->where('title_id', '=', $title_id)
            ->orderBy('date_publication', 'desc')
            ->orderBy('issue_number', 'desc')
            ->limit(1)
            ->get();
        $issue = $issue[0];

        // Clear issue id
        $issue->id = '';

        // Calculates issue number
        if(is_numeric($issue->issue_number)){
            $issue->issue_number = $issue->issue_number + 1;
        }
        else{
            $issue->issue_number = '';
        }

        // Calculates issue publication date
        $date = new \DateTime($issue->date_publication);
        $periodicity = Periodicity::find($title->periodicity_id);
        $interval = new \DateInterval('P' . $periodicity->date_interval_number . strtoupper($periodicity->date_interval));
        $date = $date->add($interval);
        $issue->date_publication = $date->format('Y-m-d');
        $issue->periodicity_id = $periodicity->id;

        // Clear synopsis
        $issue->synopsis = '';

        // Clear cover image
        $issue->image = '';

        return view("$type.form", compact('issue'));
    }

    /**
     * Store a newly created issue in storage
     *
     * @param  \App\Http\Requests\IssueRequest  $request
     * @param  \App\Issue  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(IssueRequest $request, Issue $model)
    {
        // Create title, if do not exist
        $request->merge(['title_id' => $this->storeTitle($request)]);

        // Store cover image, if uploaded
        $request->merge(['image' => $this->storeImage($request)]);

        // Create date of publication
        $request->merge(['date_publication' => $this->storeDatePublication($request)]);

        // Create issue
        $issue = $model->create($request->all());

        // Creates previous issues of the title, if they don't exist
        $this->createPreviousIssues($request, $model);

        // Redirect to show issue
        return redirect('issue/' . typeName($request->type_id) . '/' . $issue->id);
    }

    public function createPreviousIssues(IssueRequest $request, Issue $model)
    {
        // Loop issues from current to 1, checking if it exists
        if(is_numeric($request->issue_number) and $request->issue_number > 1){

            $current_number = $request->issue_number;

            for($i = $current_number - 1; $i >= 1; $i--){

                // Checks if previous issue exists
                if( ! $this->checkIssueExists($request->title_id, $i) ){

                    // Calculates publication date
                    $date = new \DateTime($request->date_publication);
                    $periodicity = Periodicity::find($request->periodicity_id);
                    $interval = new \DateInterval('P' . $periodicity->date_interval_number . strtoupper($periodicity->date_interval));
                    $date = $date->sub($interval);
                    $request->merge(['date_publication' => $date->format('Y-m-d')]);

                    // Issue number
                    $request->merge(['issue_number' => $i]);

                    // Clear synopsis and cover image
                    $request->merge(['synopsis' => '']);
                    $request->merge(['image' => '']);

                    // Create issue
                    $model->create($request->all());

                }

            }

        }

    }

    public function checkIssueExists(int $title_id, int $issue_number)
    {
        $issue = Issue::select('id')
            ->where('title_id', '=', $title_id)
            ->where('issue_number', '=', $issue_number)
            ->get();

        return count($issue);
    }

    public function storeTitle(IssueRequest $request): int
    {
        if($request->title_id == 0){
            $title = Title::create($request->all());
            return $title->id;
        }
        return $request->title_id;
    }

    public function storeImage(IssueRequest $request): string
    {
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {

            $path = 'public/covers';

            // Delete old cover image
            $this->deleteImage($request->image);

            // Upload new cover image
            $name = typeName($request->type_id) . '/' . slug($request->name, 3) . '/' . uniqid() . '_' .  slug($request->name) . '-' . $request->issue_number;
            $extension = $request->image_file->extension();
            $fullName = "{$name}.{$extension}";
            $upload = $request->image_file->storeAs($path, $fullName);

            return $fullName;
        }
        return $request->image;
    }

    public function deleteImage($image)
    {
        if($image != ''){
            Storage::delete('public/covers/' . $image);
        }
    }

    public function storeDatePublication(IssueRequest $request): string
    {
        if ($request->month_publication and is_numeric($request->month_publication) != '' and $request->year_publication != '' and is_numeric($request->year_publication)) {
            return $request->year_publication . '-' . $request->month_publication . '-01';
        }
        return '';
    }

    /**
     * Show the form for editing the specified Issue
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\View\View
     */
    public function edit(Issue $issue, $type, $id){
        $issueCollection = Issue::select('issues.*', 'titles.periodicity_id')
                ->join('titles', 'titles.id', '=', 'issues.title_id')
                ->Where('issues.id', '=', $id)
                ->get();
        $issue = $issueCollection[0];

        return view("$type.form", compact('issue'));
    }

    /**
     * Update the specified issue in storage
     *
     * @param  \App\Http\Requests\IssueRequest  $request
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(IssueRequest $request, Issue $issue)
    {
        // Store cover image, if uploaded
        $request->merge(['image' => $this->storeImage($request)]);

        // Create date of publication
        $request->merge(['date_publication' => $this->storeDatePublication($request)]);

        // Update issue data
        $issue->update($request->all());

        // Creates previous issues of the title, if they don't exist
        $this->createPreviousIssues($request, $issue);

        // Redirect to show issue
        return redirect('issue/' . typeName($request->type_id) . '/' . $issue->id)->withStatus('Dados da edição atualizados com sucesso');
    }

    /**
     * Show the form for deleting the specified Issue
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\View\View
     */
    public function delete(Issue $issue, $type, $id){
        $issueCollection = DB::select(
            "SELECT iss.*, pub.name AS publisher_name,
            (SELECT count(id) FROM reading WHERE title_id = tit.id) AS readings,
            (SELECT count(id) FROM collection WHERE issue_id = iss.id) AS collections,
            (SELECT count(id) FROM readed WHERE issue_id = iss.id) AS readeds
            FROM issues iss
            INNER JOIN titles tit ON tit.id = iss.title_id
            LEFT JOIN publishers pub ON pub.id = tit.publisher_id
            WHERE iss.id = ?",
            [$id]
        );
        $issue = $issueCollection[0];

        return view("$type.delete", compact('issue'));
    }

    /**
     * Remove the specified issue from storage
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Issue $issue)
    {
        $issueCollection = DB::select(
            "SELECT iss.image, tit.type_id
            FROM issues iss
            INNER JOIN titles tit ON tit.id = iss.title_id
            WHERE iss.id = ?",
            [$issue->id]
        );
        $type_id = $issueCollection[0]->type_id;
        $image = $issueCollection[0]->image;

        // Delete issue from collections
        DB::table('collection')->where('issue_id', '=', $issue->id)->delete();

        // Delete issue from readeds
        DB::table('readed')->where('issue_id', '=', $issue->id)->delete();

        // Delete issue
        $issue->delete();

        // Delete image cover
        $this->deleteImage($image);

        return redirect('issue/' . typeName($type_id))->withStatus(__('Edição excluída com sucesso.'));
    }

    // public function searchResult(){
    //     return view('issue.search');
    // }

    public function search(Request $request, Issue $model, string $type, string $return = ''){
        $result = 'issues';

        if($request->term == ''){
            $issues = $this->getReadingList(typeId($type));
        }
        else{
            $term = explode('#', $request->term);

            if(array_key_exists(1, $term) and is_numeric($term[1])){
                // Select ISSUES by name and number
                $issues = DB::select(
                    "SELECT iss.id, iss.name, iss.issue_number, iss.image, pub.name AS publisher_name, '0' AS issue_count, iss.date_publication, col.id AS collection, red.id AS readed, tit.id AS title_id
                    FROM issues iss
                    INNER JOIN titles tit ON iss.title_id = tit.id
                    LEFT JOIN publishers pub ON tit.publisher_id = pub.id
                    LEFT JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
                    LEFT JOIN readed red ON iss.id = red.issue_id AND red.user_id = ?
                    WHERE tit.type_id = ?
                    AND iss.name LIKE ?
                    AND iss.issue_number = ?
                    ORDER BY iss.date_publication DESC, iss.name ASC
                    LIMIT 20",
                    [\Auth::id(), \Auth::id(), typeId($type), termToSearch($term[0]), $term[1]]
                );
            }
            else{
                // Select TITLES by name
                $issues = DB::select(
                    "SELECT tit.id, tit.name, pub.name AS publisher_name, '' AS issue_number, '' AS date_publication, rng.id AS reading,
                        (SELECT date_publication FROM issues WHERE title_id = tit.id ORDER BY date_publication DESC LIMIT 1) AS last_issue_date,
                        (SELECT image FROM issues WHERE title_id = tit.id AND (image IS NOT NULL AND image <> ?) ORDER BY date_publication DESC LIMIT 1) AS image,
                        (SELECT COUNT(id) FROM issues WHERE title_id = tit.id) AS issue_count
                    FROM titles tit
                    LEFT JOIN publishers pub ON tit.publisher_id = pub.id
                    LEFT JOIN reading rng ON tit.id = rng.title_id AND rng.user_id = ?
                    WHERE tit.type_id = ?
                    AND (tit.name LIKE ? OR pub.name LIKE ?)
                    ORDER BY last_issue_date DESC, tit.name ASC
                    LIMIT 20",
                    ['', \Auth::id(), typeId($type), termToSearch($request->term), termToSearch($request->term)]
                );

                $result = 'titles';
            }
        }

        switch ($return) {
            case 'index':
                return view('comics.index', ['issues' => $issues, 'search' => $request->term, 'result' => $result]);
                break;

            default:
                return view('comics.grid', ['issues' => $issues, 'result' => $result]);
                break;
        }

    }

}
