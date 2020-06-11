<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{

    protected $fillable = [
        'name',
    ];

    public function subgenres(){
        return $this->hasMany('App\Subgenre');
    }

    public function titles()
    {
        return $this->hasMany('App\Title');
    }
}
