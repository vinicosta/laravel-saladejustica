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
        return view("$type.index");
    }

    public function show(Issue $model, string $type, int $id)
    {
        $issue = $model->find($id);

        return view("$type.show", ['issue' => $issue]);
    }

    /**
     * Show the form for creating a new subgenre
     *
     * @return \Illuminate\View\View
     */
    public function create(Issue $issue, string $type)
    {
        return view("$type.create", compact('issue'));
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
        return redirect('issue/' . typeName($request->type_id) . '/show/' . $issue->id);
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
            ->Where('issue_number', '=', $issue_number)
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
            $name = typeName($request->type_id) . '/' . slug($request->name, 3) . '/' . uniqid() . '_' .  slug($request->name) . '-' . $request->issue_number;
            $extension = $request->image_file->extension();
            $fullName = "{$name}.{$extension}";
            $upload = $request->image_file->storeAs('covers', $fullName);
            return $fullName;
        }
        return '';
    }

    public function storeDatePublication(IssueRequest $request): string
    {
        if ($request->month_publication and is_numeric($request->month_publication) != '' and $request->year_publication != '' and is_numeric($request->year_publication)) {
            return $request->year_publication . '-' . $request->month_publication . '-01';
        }
        return '';
    }

    /**
     * Show the form for editing the specified Subgenre
     *
     * @param  \App\Subgenre  $subgenre
     * @return \Illuminate\View\View
     */
    public function edit(Issue $issue){
        // $subgenre = DB::table('subgenres')
        //     ->join('genres', 'genres.id', '=', 'subgenres.genre_id')
        //     ->select('subgenres.*', 'genres.name as genre_name');
            //->where('subgenre.id', '=', $record->id);

        return view('subgenres.form', compact('subgenre'));
    }

    /**
     * Update the specified Subgenre in storage
     *
     * @param  \App\Http\Requests\IssueRequest  $request
     * @param  \App\Subgenre  $subgenre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(IssueRequest $request, Issue $issue)
    {
        $subgenre->update(
            $request->all()
        );

        return redirect()->route('subgenre.index')->withStatus(__('Subgênero atualizado com sucesso.'));
    }

    /**
     * Remove the specified Subgenre from storage
     *
     * @param  \App\Subgenre  $subgenre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Issue $issue)
    {
        $subgenre->delete();

        return redirect()->route('subgenre.index')->withStatus(__('Subgênero excluído com sucesso.'));
    }

    public function searchResult(){
        return view('subgenre.search');
    }

    public function search(Request $request, Issue $model, string $type){
        if($request->term == ''){
            return null;
        }

        $term = explode('#', $request->term);

        if(array_key_exists(1, $term) and is_numeric($term[1])){
            $issues = 'teste';
        }
        else{
            $issues = DB::select(
                "SELECT tit.name AS title_name, pub.name AS publisher_name,
                    (SELECT date_publication FROM issues WHERE title_id = tit.id ORDER BY date_publication DESC LIMIT 1) AS last_issue_date,
                    (SELECT image FROM issues WHERE title_id = tit.id AND image IS NOT NULL ORDER BY date_publication DESC LIMIT 1) AS last_issue_image,
                    (SELECT COUNT(id) FROM issues WHERE title_id = tit.id) AS issue_count
                FROM titles tit
                LEFT JOIN publishers pub ON tit.publisher_id = pub.id
                WHERE tit.type_id = ?
                AND (tit.name LIKE ? OR pub.name LIKE ?)
                ORDER BY last_issue_date DESC, tit.name ASC
                LIMIT 20",
                [typeId($type), termToSearch($request->term), termToSearch($request->term)]
            );
        }

        return view('comics.grid', compact('issues'));
    }

}
