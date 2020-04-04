<?php

namespace App\Http\Controllers;

use App\Subgenre;
use Illuminate\Http\Request;
use App\Http\Requests\SubgenreRequest;
use DB;

class SubgenreController extends Controller
{
    /**
     * Return all records of the resource
     *
     * @param  \App\Subgenre  $model
     */
    private function getAll(Subgenre $model){
        $subgenres = $model->with('genre')->get();

        return $subgenres;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Subgenre $model)
    {
        $subgenres = $this->getAll($model);

        return view('subgenres.index', ['subgenres' => $subgenres]);
    }

    /**
     * Show the form for creating a new subgenre
     *
     * @return \Illuminate\View\View
     */
    public function create(Subgenre $subgenre)
    {
        return view('subgenres.form', compact('subgenre'));
    }

    /**
     * Store a newly created subgenre in storage
     *
     * @param  \App\Http\Requests\SubgenreRequest  $request
     * @param  \App\Subgenre  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SubgenreRequest $request, Subgenre $model)
    {
        $model->create($request->all());

        return redirect()->route('subgenre.index')->withStatus(__('Subgênero criado com sucesso.'));
    }

    /**
     * Show the form for editing the specified Subgenre
     *
     * @param  \App\Subgenre  $subgenre
     * @return \Illuminate\View\View
     */
    public function edit(Subgenre $subgenre){
        // $subgenre = DB::table('subgenres')
        //     ->join('genres', 'genres.id', '=', 'subgenres.genre_id')
        //     ->select('subgenres.*', 'genres.name as genre_name');
            //->where('subgenre.id', '=', $record->id);

        return view('subgenres.form', compact('subgenre'));
    }

    /**
     * Update the specified Subgenre in storage
     *
     * @param  \App\Http\Requests\SubgenreRequest  $request
     * @param  \App\Subgenre  $subgenre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SubgenreRequest $request, Subgenre $subgenre)
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
    public function destroy(Subgenre $subgenre)
    {
        $subgenre->delete();

        return redirect()->route('subgenre.index')->withStatus(__('Subgênero excluído com sucesso.'));
    }

    public function searchResult(){
        return view('subgenre.search');
    }

    public function search(Request $request, Subgenre $model, string $return){
        if($request->term == ''){
            $subgenres = $this->getAll($model);

            return view('subgenres.grid', ['subgenres' => $subgenres]);
        }

        $subgenres = $model->select('subgenres.*', 'genres.name as genre_name')
            ->join('genres', 'genres.id', '=', 'subgenres.genre_id')
            ->where('subgenres.name', 'LIKE', '%' . str_replace(' ', '%', trim($request->term)) . '%')
            ->orWhere('genres.name', 'LIKE', '%' . str_replace(' ', '%', trim($request->term)) . '%')
            ->orderBy('subgenres.name')
            ->orderBy('genres.name')
            ->get();

        switch ($return) {
            case 'json':
                $response = array();

                foreach($subgenres as $subgenre){
                    $response[] = array("id" => $subgenre->id, "label" => $subgenre->name);
                }

                return \Response::json($response);
                break;

            case 'index':
                return view('subgenres.index', ['subgenres' => $subgenres, 'search' => $request->term]);
                break;

            default:
                return view('subgenres.grid', compact('subgenres'));
                break;
        }
    }
}
