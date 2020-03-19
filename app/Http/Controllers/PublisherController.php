<?php

namespace App\Http\Controllers;

use DB;
use App\Publisher;
use Illuminate\Http\Request;
use App\Http\Requests\PublisherRequest;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Publisher $model)
    {
        return view('publishers.index', ['publishers' => $model->paginate(15)]);
    }

    /**
     * Show the form for creating a new publisher
     *
     * @return \Illuminate\View\View
     */
    public function create(Publisher $publisher)
    {
        return view('publishers.form', compact('publisher'));
    }

    /**
     * Store a newly created publisher in storage
     *
     * @param  \App\Http\Requests\PublisherRequest  $request
     * @param  \App\Publisher  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PublisherRequest $request, Publisher $model)
    {
        $model->create($request->all());

        return redirect()->route('publisher.index')->withStatus(__('Editora criada com sucesso.'));
    }

    /**
     * Show the form for editing the specified publisher
     *
     * @param  \App\Publisher  $publisher
     * @return \Illuminate\View\View
     */
    public function edit(Publisher $publisher)
    {
        return view('publishers.form', compact('publisher'));
    }

    /**
     * Update the specified publisher in storage
     *
     * @param  \App\Http\Requests\PublisherRequest  $request
     * @param  \App\Publisher  $publisher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PublisherRequest $request, Publisher $publisher)
    {
        $publisher->update(
            $request->all()
        );

        return redirect()->route('publisher.index')->withStatus(__('Editora atualizada com sucesso.'));
    }

    /**
     * Remove the specified publisher from storage
     *
     * @param  \App\Publisher  $publisher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publisher.index')->withStatus(__('Editora excluída com sucesso.'));
    }

    public function searchResult()
    {
        return view('publisher.search');
    }

    public function search(Request $request, Publisher $model, string $return)
    {
        if($request->term == ''){
            $publishers = $model->paginate(15);
        }

        $publishers = $model->where('name', 'LIKE', '%' . str_replace(' ', '%', trim($request->term)) . '%')
            ->orderBy('name')
            ->get();

        if($return == 'json'){
            $response = array();

            foreach($publishers as $publisher){
                $response[] = array("id" => $publisher->id, "label" => $publisher->name);
            }

            return \Response::json($response);
        }

        return view('publishers.grid', compact('publishers'));
    }
}
