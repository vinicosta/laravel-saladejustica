<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{

    protected $fillable = [
        'name',
        'type_id',
        'publisher_id',
        'periodicity_id',
        'size_id',
        'genre_id',
        'subgenre_id'
    ];

    public function publisher()
    {
        return $this->belongsTo('App\Publisher');
    }

    public function periodicity()
    {
        return $this->belongsTo('App\Periodicity');
    }

    public function size()
    {
        return $this->belongsTo('App\Size');
    }

    public function genre()
    {
        return $this->belongsTo('App\Genre');
    }

    public function subgenre()
    {
        return $this->belongsTo('App\Subgenre');
    }

    public function issues()
    {
        return $this->hasMany('App\Issue');
    }

    public function readings()
    {
        return $this->hasMany('App\Reading');
    }

}
