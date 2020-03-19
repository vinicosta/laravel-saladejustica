<?php

namespace App\Http\Controllers;

use DB;
use App\Author;
use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Author $model)
    {
        return view('authors.index', ['authors' => $model->paginate(15)]);
    }

    /**
     * Show the form for creating a new author
     *
     * @return \Illuminate\View\View
     */
    public function create(Author $author)
    {
        return view('authors.form', compact('author'));
    }

    /**
     * Store a newly created author in storage
     *
     * @param  \App\Http\Requests\AuthorRequest  $request
     * @param  \App\Author  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AuthorRequest $request, Author $model)
    {
        $model->create($request->all());

        return redirect()->route('author.index')->withStatus(__('Autor criado com sucesso.'));
    }

    /**
     * Show the form for editing the specified author
     *
     * @param  \App\Author  $author
     * @return \Illuminate\View\View
     */
    public function edit(Author $author)
    {
        return view('authors.form', compact('author'));
    }

    /**
     * Update the specified author in storage
     *
     * @param  \App\Http\Requests\AuthorRequest  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AuthorRequest $request, author $author)
    {
        $author->update(
            $request->all()
        );

        return redirect()->route('author.index')->withStatus(__('Autor atualizado com sucesso.'));
    }

    /**
     * Remove the specified author from storage
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('author.index')->withStatus(__('Autor excluído com sucesso.'));
    }

    public function searchResult(){
        return view('author.search');
    }

    public function search(Request $request, Author $model, string $return){
        if($request->term == ''){
            $authors = $model->paginate(15);
        }

        $authors = $model->where('name', 'LIKE', '%' . str_replace(' ', '%', trim($request->term)) . '%')
            ->orderBy('name')
            ->get();

        if($return == 'json'){
            $response = array();

            foreach($authors as $author){
                $response[] = array("id" => $author->id, "label" => $author->name);
            }

            return \Response::json($response);
        }

        return view('authors.grid', compact('authors'));
    }
}
