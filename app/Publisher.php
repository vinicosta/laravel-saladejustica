<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{

    protected $fillable = [
        'name',
    ];

    public function titles()
    {
        return $this->hasMany('App\Title');
    }

}
