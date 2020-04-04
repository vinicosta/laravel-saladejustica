<?php

namespace App\Http\Controllers;

use DB;
use App\Size;
use Illuminate\Http\Request;
use App\Http\Requests\SizeRequest;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Size $model)
    {
        return view('sizes.index', ['sizes' => $model->paginate(15)]);
    }

    /**
     * Show the form for creating a new size
     *
     * @return \Illuminate\View\View
     */
    public function create(Size $size)
    {
        return view('sizes.form', compact('size'));
    }

    /**
     * Store a newly created size in storage
     *
     * @param  \App\Http\Requests\SizeRequest  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SizeRequest $request, Size $size)
    {
        $size->create(
            $request->all()
        );

        return redirect()->route('size.index')->withStatus(__('Tamanho criado com sucesso.'));
    }

    /**
     * Show the form for editing the specified size
     *
     * @param  \App\Size  $size
     * @return \Illuminate\View\View
     */
    public function edit(Size $size)
    {
        return view('sizes.form', compact('size'));
    }

    /**
     * Update the specified size in storage
     *
     * @param  \App\Http\Requests\SizeRequest  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SizeRequest $request, Size $size)
    {
        $size->update(
            $request->all()
        );

        return redirect()->route('size.index')->withStatus(__('Tamanho atualizado com sucesso.'));
    }

    /**
     * Remove the specified size from storage
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->route('size.index')->withStatus(__('Tamanho excluído com sucesso.'));
    }

    public function searchResult()
    {
        return view('size.search');
    }

    public function search(Request $request, Size $model, string $return)
    {
        if($request->term == ''){
            $sizes = $model->paginate(15);
        }

        $sizes = $model->where('name', 'LIKE', '%' . str_replace(' ', '%', trim($request->term)) . '%')
            ->orderBy('name')
            ->get();

        switch ($return) {
            case 'json':
                $response = array();

                foreach($sizes as $size){
                    $response[] = array("id" => $size->id, "label" => $size->name);
                }

                return \Response::json($response);
                break;

            case 'index':
                return view('sizes.index', ['sizes' => $sizes, 'search' => $request->term]);
                break;

            default:
                return view('sizes.grid', compact('sizes'));
                break;
        }

    }
}
