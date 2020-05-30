<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subgenre extends Model
{

    protected $fillable = [
        'genre_id', 'name',
    ];

    public function genre()
    {
        return $this->belongsTo('App\Genre');
    }

}
