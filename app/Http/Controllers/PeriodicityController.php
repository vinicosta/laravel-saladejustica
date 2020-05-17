<?php

namespace App\Http\Controllers;

use DB;
use App\Periodicity;
use Illuminate\Http\Request;

class PeriodicityController extends Controller
{
    public function search(Request $request, Periodicity $model, string $return)
    {
        if($request->term == ''){
            $periodicities = $model->paginate(15);
        }

        $periodicities = $model->where('name', 'LIKE', '%' . str_replace(' ', '%', trim($request->term)) . '%')
            ->orderBy('name')
            ->get();

        switch ($return) {
            case 'json':
                $response = array();

                foreach($periodicities as $publisher){
                    $response[] = array("id" => $publisher->id, "label" => $publisher->name);
                }

                return \Response::json($response);
                break;

            case 'index':
                return view('periodicities.index', ['periodicities' => $periodicities, 'search' => $request->term]);
                break;

            default:
                return view('periodicities.grid', compact('periodicities'));
                break;
        }

    }

    public function find(Periodicity $model, int $id)
    {
        return $model->find($id);
    }
}
