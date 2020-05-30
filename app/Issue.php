<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{

    protected $fillable = [
        'title_id',
        'name',
        'subtitle',
        'issue_number',
        'date_publication',
        'number_pages',
        'isbn',
        'synopsis',
        'image'
    ];

    public function title()
    {
        return $this->belongsTo('App\Title');
    }

    public function colections()
    {
        return $this->hasMany('App\Collection');
    }

    public function readeds()
    {
        return $this->hasMany('App\Readed');
    }

}
