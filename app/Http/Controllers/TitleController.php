<?php

namespace App\Http\Controllers;

use DB;
use App\Title;
use App\Issue;
use App\Periodicity;
use Illuminate\Http\Request;
use App\Http\Requests\TitleRequest;
use Illuminate\Support\Facades\Storage;

class TitleController extends Controller
{
    public function show(Title $model, string $type, int $id)
    {
        // Get title
        $title = DB::select(
            "SELECT tit.id, tit.name, pub.name AS publisher_name, per.name AS periodicity_name, siz.name AS size_name, gen.name AS genre_name, sgr.name AS subgenre_name, rnd.id AS reading,
	            (SELECT COUNT(id) FROM issues WHERE title_id = tit.id) AS issues_count
            FROM titles tit
            LEFT JOIN publishers pub ON tit.publisher_id = pub.id
            LEFT JOIN periodicities per ON tit.periodicity_id = per.id
            LEFT JOIN sizes siz ON tit.size_id = siz.id
            LEFT JOIN genres gen ON tit.genre_id = gen.id
            LEFT JOIN subgenres sgr ON tit.subgenre_id = sgr.id
            LEFT JOIN reading rnd ON tit.id = rnd.title_id AND rnd.user_id = ?
            WHERE tit.id = ?",
            [\Auth::id(), ($id)]
        );

        // Get issues
        $issues = Issue::select('*')
            ->where('title_id', '=', $id)
            ->orderBy('date_publication', 'desc')
            ->orderBy('issue_number', 'desc')
            ->get();

        return view("$type.title.show", ['title' => $title[0], 'issues' => $issues]);
    }

    /**
     * Show the form for editing title
     *
     * @param  \App\Title  $title
     * @return \Illuminate\View\View
     */
    public function edit(Title $title, $type, $id){
        $title = Title::find($id);
        // $title = $title[0];

        return view("$type.title.form", compact('title'));
    }

    /**
     * Update the specified issue in storage
     *
     * @param  \App\Http\Requests\TitleRequest  $request
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TitleRequest $request, Issue $issue)
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
        return redirect('issue/' . typeName($request->type_id) . '/' . $issue->id);
    }

    /**
     * Show the form for deleting the specified Issue
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\View\View
     */
    public function delete(Title $issue, $type, $id){
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
    public function destroy(Title $issue)
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
                        (SELECT image FROM issues WHERE title_id = tit.id AND image IS NOT NULL ORDER BY date_publication DESC LIMIT 1) AS image,
                        (SELECT COUNT(id) FROM issues WHERE title_id = tit.id) AS issue_count
                    FROM titles tit
                    LEFT JOIN publishers pub ON tit.publisher_id = pub.id
                    LEFT JOIN reading rng ON tit.id = rng.title_id AND rng.user_id = ?
                    WHERE tit.type_id = ?
                    AND (tit.name LIKE ? OR pub.name LIKE ?)
                    ORDER BY last_issue_date DESC, tit.name ASC
                    LIMIT 20",
                    [\Auth::id(), typeId($type), termToSearch($request->term), termToSearch($request->term)]
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
