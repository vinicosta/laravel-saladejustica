<?php

namespace App\Http\Controllers;

use DB;
use App\Readed;
use App\Reading;
use Illuminate\Http\Request;

class ReadedController extends Controller
{
    public function store(Readed $model, Request $request)
    {
        // Check if is reading title of issue
        $reading = DB::select(
            "SELECT id FROM reading WHERE title_id = ? AND user_id = ?",
            [$request->title_id, \Auth::id()]
        );
        if(!$reading){
            Reading::create($request->all());
        }
        
        // Create date
        $request->merge(['readed_date' => date('Y-m-d')]);
        
        // Create readed
        $model->create($request->all());
    }

    public function destroy(Readed $model, Request $request)
    {
        DB::table('readed')
            ->where('issue_id', '=', $request->issue_id)
            ->where('user_id', '=', $request->user_id)
            ->delete();
    }

}
