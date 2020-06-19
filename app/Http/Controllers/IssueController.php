<?php

namespace App\Http\Controllers;

use DB;
use App\Issue;
use App\Title;
use App\Periodicity;
use App\Author_Issue;
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

    public function getReadingList(int $type_id)
    {
        // Get reading titles
        $titles = Title::select('titles.id')
            ->join('reading', 'titles.id', '=', 'reading.title_id')
            ->where('titles.type_id', '=', $type_id)
            ->where('reading.user_id', '=', \Auth::id())
            ->get();

        $issues = [];

        // Loop over reading titles and checking if had unread issues
        foreach($titles as $title){
            $nextIssue = $this->getNextReading($title->id, $type_id);
            if($nextIssue){
                $issues[] = $nextIssue;
            }
        }

        // Sort by issue publication date
        usort($issues, fn($a, $b) => strcmp($a->date_publication, $b->date_publication));

        return $issues;
    }

    public function getNextReading($title_id, $type_id){

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
            $orderFilter = "
                AND iss.date_publication >= (SELECT issues.date_publication FROM issues INNER JOIN readed ON issues.id = readed.issue_id WHERE issues.title_id = ? AND readed.user_id = ? ORDER BY issues.date_publication DESC, issues.issue_number DESC LIMIT 1)
            ";

            if($type_id == 2){
                $orderFilter = "
                    AND iss.issue_number > (SELECT issues.issue_number FROM issues INNER JOIN readed ON issues.id = readed.issue_id WHERE issues.title_id = ? AND readed.user_id = ? ORDER BY issues.issue_number DESC LIMIT 1)
                ";
            }

            $issue = DB::select(
                "SELECT iss.id, iss.name, iss.issue_number, iss.image, iss.date_publication, pub.name AS publisher_name, '0' AS issue_count, col.id AS collection, '0' AS readed, tit.id AS title_id
                FROM issues iss
                INNER JOIN titles tit ON tit.id = iss.title_id
                LEFT JOIN publishers pub ON pub.id = tit.publisher_id
                LEFT JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
                WHERE iss.title_id = ?
                AND iss.id NOT IN (SELECT issues.id FROM issues INNER JOIN readed ON issues.id = readed.issue_id WHERE issues.title_id = ? AND readed.user_id = ?)
                $orderFilter
                ORDER BY iss.date_publication, iss.issue_number
                LIMIT 1",
                [\Auth::id(), $title_id, $title_id, \Auth::id(), $title_id, \Auth::id()]
            );

        }

        if(!$issue){
            return null;
        }
        return $issue[0];
    }

    public function show(Issue $model, string $type, int $id)
    {
        $nav_issues =
            "(SELECT id FROM issues WHERE title_id = tit.id AND date_publication < iss.date_publication ORDER BY date_publication ASC, issue_number ASC LIMIT 1) AS first_issue,
            (SELECT id FROM issues WHERE title_id = tit.id AND date_publication < iss.date_publication ORDER BY date_publication DESC, issue_number DESC LIMIT 1) AS previous_issue,
            (SELECT id FROM issues WHERE title_id = tit.id AND date_publication > iss.date_publication ORDER BY date_publication ASC, issue_number ASC LIMIT 1) AS next_issue,
            (SELECT id FROM issues WHERE title_id = tit.id AND date_publication > iss.date_publication ORDER BY date_publication DESC, issue_number DESC LIMIT 1) AS last_issue ";
        if($type == 'books'){
            $nav_issues =
                "(SELECT id FROM issues WHERE title_id = tit.id AND issue_number < iss.issue_number ORDER BY issue_number ASC LIMIT 1) AS first_issue,
                (SELECT id FROM issues WHERE title_id = tit.id AND issue_number < iss.issue_number ORDER BY issue_number DESC LIMIT 1) AS previous_issue,
                (SELECT id FROM issues WHERE title_id = tit.id AND issue_number > iss.issue_number ORDER BY issue_number ASC LIMIT 1) AS next_issue,
                (SELECT id FROM issues WHERE title_id = tit.id AND issue_number > iss.issue_number ORDER BY issue_number DESC, issue_number DESC LIMIT 1) AS last_issue ";
        }

        $issue = DB::select(
            "SELECT iss.id, iss.name, iss.subtitle, iss.issue_number, iss.image, iss.date_publication, iss.title_id, iss.synopsis, iss.isbn, pub.name AS publisher_name, col.id AS collection, col.added_date, red.id AS readed, red.readed_date, gen.name AS genre_name, sgr.name AS subgenre_name, siz.name AS size_name, tit.id AS title_id, tit.name AS title_name,"
            . $nav_issues .
            "FROM issues iss
            INNER JOIN titles tit ON iss.title_id = tit.id
            LEFT JOIN publishers pub ON tit.publisher_id = pub.id
            LEFT JOIN genres gen ON tit.genre_id = gen.id
            LEFT JOIN subgenres sgr ON tit.subgenre_id = sgr.id
            LEFT JOIN sizes siz ON tit.size_id = siz.id
            LEFT JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
            LEFT JOIN readed red ON iss.id = red.issue_id AND red.user_id = ?
            WHERE iss.id = ?",
            [\Auth::id(), \Auth::id(), ($id)]
        );

        // Get authors of issue
        $authors = $this->getAuthors($type, $id);

        return view("$type.show", ['issue' => $issue[0], 'authors' => $authors]);
    }

    public function getAuthors($type, $issue_id){

        $authors = null;

        if($type == 'books'){
            $authors = DB::select(
                "SELECT aut.id, aut.name
                FROM authors aut
                INNER JOIN author_issue ais ON aut.id = ais.author_id
                WHERE ais.issue_id = ?
                ORDER BY aut.name",
                [$issue_id]
            );
        }

        return $authors;
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

        // Get authors of issue
        $authors = $this->getAuthors($type, $issue->id);

        // Clear issue id
        $issue->id = '';

        // If it is book, uses title name as issue name
        if($type == 'books'){
            $issue->name = $title->name;
        }

        // Calculates issue number
        if(is_numeric($issue->issue_number)){
            $issue->issue_number = $issue->issue_number + 1;
        }
        else{
            $issue->issue_number = '';
        }

        // If it is not a book, calculates issue publication date
        if($type != 'books'){
            $issue->date_publication = $this->calcPublicationDate($issue->date_publication, $title->periodicity_id, '+');
        }

        // Periodicity
        $issue->periodicity_id = $title->periodicity_id;

        // Clear synopsis
        $issue->synopsis = '';

        // Clear cover image
        $issue->image = '';

        return view("$type.form", ['issue' => $issue, 'authors' => $authors]);
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

        // For comics and magazines only, creates previous issues of the title, if they don't exist
        if($request->type_id != 2){
            $this->createPreviousIssues($request, $model);
        }

        // Store authors
        $this->storeAuthors($issue->id, $request);

        // Redirect to show issue
        return redirect('issue/' . typeName($request->type_id) . '/' . $issue->id);
    }

    public function storeAuthors($issue_id, $request){
        if($request->authors_id != ''){

            $authors = explode(',', $request->authors_id);

            // Delete authors not in list
            DB::table('author_issue')
                ->where('issue_id', '=', $issue_id)
                ->whereNotIn('author_id', $authors)
                ->delete();

            // Loops authors, check if exists and create if not
            foreach ($authors as $author_id) {

                if($author_id){
                    $author_issue = DB::table('author_issue')
                        ->where('issue_id', '=', $issue_id)
                        ->where('author_id', '=', $author_id)
                        ->get();

                    if(!count($author_issue)){
                        $request->merge(['issue_id' => $issue_id]);
                        $request->merge(['author_id' => $author_id]);
                        Author_Issue::create($request->all());
                    }
                }

            }

        }
        else{

            // Delete all authors
            DB::table('author_issue')
                ->where('issue_id', '=', $issue_id)
                ->delete();

        }
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
                    $request->merge(['date_publication' => $this->calcPublicationDate($request->date_publication, $request->periodicity_id, '-')]);

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

    public function calcPublicationDate($date_publication, $periodicity_id, $operation){
        $date = new \DateTime($date_publication);
        $periodicity = Periodicity::find($periodicity_id);
        $interval = new \DateInterval('P' . $periodicity->date_interval_number . strtoupper($periodicity->date_interval));

        if($operation == '+'){
            $date = $date->add($interval);
        }
        else{
            $date = $date->sub($interval);
        }

        return $date->format('Y-m-d');
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

    public function storeImage(IssueRequest $request)
    {
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {

            $path = 'public/covers';

            // Delete old cover image
            deleteImage($request->image);

            // Upload new cover image
            $name = typeName($request->type_id) . '/' . slug($request->name, 3) . '/' . uniqid() . '_' .  slug($request->name) . '-' . $request->issue_number;
            $extension = $request->image_file->extension();
            $fullName = "{$name}.{$extension}";
            $upload = $request->image_file->storeAs($path, $fullName);

            return $fullName;
        }
        return $request->image;
    }

    public function storeDatePublication(IssueRequest $request)
    {
        if ($request->month_publication and is_numeric($request->month_publication) != '' and $request->year_publication != '' and is_numeric($request->year_publication)) {
            return $request->year_publication . '-' . $request->month_publication . '-01';
        }
        return NULL;
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

        // Get authors of issue
        $authors = $this->getAuthors($type, $id);

        return view("$type.form", ['issue' => $issue, 'authors' => $authors]);
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

        // For comics and magazines only, creates previous issues of the title, if they don't exist
        if($request->type_id != 2){
            $this->createPreviousIssues($request, $issue);
        }

        // Store authors
        $this->storeAuthors($issue->id, $request);

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
            "SELECT iss.image, tit.type_id, iss.title_id
            FROM issues iss
            INNER JOIN titles tit ON tit.id = iss.title_id
            WHERE iss.id = ?",
            [$issue->id]
        );
        $type_id = $issueCollection[0]->type_id;
        $image = $issueCollection[0]->image;
        $title_id = $issueCollection[0]->title_id;

        // Delete issue from collections
        DB::table('collection')->where('issue_id', '=', $issue->id)->delete();

        // Delete issue from readeds
        DB::table('readed')->where('issue_id', '=', $issue->id)->delete();

        // Delete issue
        $issue->delete();

        // Delete image cover
        deleteImage($image);

        // Check if title has no more issues
        $title = DB::table('issues')
            ->where('title_id', '=', $title_id)
            ->get();
        if(!count($title)){
            DB::table('titles')
                ->where('id', '=', $title_id)
                ->delete();

            DB::table('reading')
                ->where('title_id', '=', $title_id)
                ->delete();
        }

        return redirect('issue/' . typeName($type_id))->withStatus(__('Edição excluída com sucesso.'));
    }

    public function search(Request $request, Issue $model, string $type, string $return = ''){
        $result = 'issues';

        if($request->term == ''){
            $issues = $this->getReadingList(typeId($type));
        }
        else{
            $term = explode('#', $request->term);

            if($type != 'books'){

                // If type is comics or magazines
                if(array_key_exists(1, $term) and is_numeric($term[1])){
                    // Select ISSUES by name and number
                    $issues = $this->searchIssues(typeId($type), termToSearch($term[0]), $term[1]);
                }
                else{
                    // Select TITLES by name
                    $issues = $this->searchTitles(typeId($type), $request->term);

                    $result = 'titles';
                }

            }
            else{

                // if type is books
                $issues = $this->searchBooks(typeId($type), $request->term);
            }
        }

        switch ($return) {
            case 'index':
                return view("$type.index", ['issues' => $issues, 'search' => $request->term, 'result' => $result]);
                break;

            default:
                return view("$type.grid", ['issues' => $issues, 'result' => $result]);
                break;
        }

    }

    public function searchIssues($type_id, $name, $issue_number){
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
            ORDER BY iss.date_publication DESC, iss.name ASC",
            [\Auth::id(), \Auth::id(), $type_id, termToSearch($name), $issue_number]
        );

        return $issues;
    }

    public function searchTitles($type_id, $name){
        $titles = DB::select(
            "SELECT tit.id, tit.name, pub.name AS publisher_name, '' AS issue_number, '' AS date_publication, rng.id AS reading,
                (SELECT date_publication FROM issues WHERE title_id = tit.id ORDER BY date_publication DESC LIMIT 1) AS last_issue_date,
                (SELECT image FROM issues WHERE title_id = tit.id AND (image IS NOT NULL AND image <> ?) ORDER BY date_publication DESC LIMIT 1) AS image,
                (SELECT COUNT(id) FROM issues WHERE title_id = tit.id) AS issue_count
            FROM titles tit
            LEFT JOIN publishers pub ON tit.publisher_id = pub.id
            LEFT JOIN reading rng ON tit.id = rng.title_id AND rng.user_id = ?
            WHERE tit.type_id = ?
            AND (tit.name LIKE ? OR pub.name LIKE ?)
            ORDER BY last_issue_date DESC, tit.name ASC",
            ['', \Auth::id(), $type_id, termToSearch($name), termToSearch($name)]
        );

        return $titles;
    }

    public function searchBooks($type_id, $name){
        $issues = DB::select(
            "SELECT iss.id, iss.name, iss.issue_number, iss.image, pub.name AS publisher_name, '0' AS issue_count, iss.date_publication, col.id AS collection, red.id AS readed, tit.id AS title_id
            FROM issues iss
            INNER JOIN titles tit ON iss.title_id = tit.id
            LEFT JOIN publishers pub ON tit.publisher_id = pub.id
            LEFT JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
            LEFT JOIN readed red ON iss.id = red.issue_id AND red.user_id = ?
            WHERE tit.type_id = ?
            AND (iss.name LIKE ?
            OR tit.name LIKE ?)
            ORDER BY iss.name ASC",
            [\Auth::id(), \Auth::id(), $type_id, termToSearch($name), termToSearch($name)]
        );

        return $issues;
    }

}
