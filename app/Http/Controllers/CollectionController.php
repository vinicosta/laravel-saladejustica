<?php

namespace App\Http\Controllers;

use DB;
use App\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function store(Collection $model, Request $request)
    {
        // Create date
        $request->merge(['added_date' => date('Y-m-d')]);
        
        $model->create(
            $request->all()
        );
    }

    public function destroy(Collection $model, Request $request)
    {
        DB::table('collection')
            ->where('issue_id', '=', $request->issue_id)
            ->where('user_id', '=', $request->user_id)
            ->delete();
    }

}
