<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{

    protected $fillable = [
        'name',
        'type_id'
    ];

    public function titles()
    {
        return $this->hasMany('App\Title');
    }

}
