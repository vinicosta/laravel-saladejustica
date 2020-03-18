<?php

namespace App\Http\Controllers;

use DB;
use App\Genre;
use Illuminate\Http\Request;
use App\Http\Requests\GenreRequest;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Genre $model)
    {
        return view('genres.index', ['genres' => $model->paginate(15)]);
    }

    /**
     * Show the form for creating a new genre
     *
     * @return \Illuminate\View\View
     */
    public function create(Genre $genre)
    {
        return view('genres.form', compact('genre'));
    }

    /**
     * Store a newly created genre in storage
     *
     * @param  \App\Http\Requests\GenreRequest  $request
     * @param  \App\Genre  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GenreRequest $request, Genre $model)
    {
        $model->create($request->all());

        return redirect()->route('genre.index')->withStatus(__('Gênero criado com sucesso.'));
    }

    /**
     * Show the form for editing the specified genre
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\View\View
     */
    public function edit(Genre $genre)
    {
        return view('genres.form', compact('genre'));
    }

    /**
     * Update the specified genre in storage
     *
     * @param  \App\Http\Requests\GenreRequest  $request
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GenreRequest $request, Genre $genre)
    {
        $genre->update(
            $request->all()
        );

        return redirect()->route('genre.index')->withStatus(__('Gênero atualizado com sucesso.'));
    }

    /**
     * Remove the specified genre from storage
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('genre.index')->withStatus(__('Gênero excluído com sucesso.'));
    }

    public function searchResult(){
        return view('genre.search');
    }

    public function search(Request $request, Genre $model, $return){
        if($request->term == ''){
            $genres = $model->paginate(15);
        }

        $genres = $model->where('name', 'LIKE', '%' . str_replace(' ', '%', trim($request->term)) . '%')
            ->orderBy('name')
            ->get();

        if($return == 'json'){
            $response = array();

            foreach($genres as $genre){
                $response[] = array("id" => $genre->id, "label" => $genre->name);
            }

            return \Response::json($response);
        }

        return view('genres.grid', compact('genres'));
    }
}
