<?php

namespace App\Http\Controllers;

use DB;
use App\Reading;
use Illuminate\Http\Request;

class ReadingController extends Controller
{
    public function store(Reading $model, Request $request)
    {
        $model->create(
            $request->all()
        );
    }

    public function destroy(Reading $model, Request $request)
    {
        DB::table('reading')
            ->where('title_id', '=', $request->title_id)
            ->where('user_id', '=', $request->user_id)
            ->delete();
    }

}
