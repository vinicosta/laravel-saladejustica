<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

    protected $table = 'collection';

    protected $fillable = [
        'issue_id',
        'user_id',
        'added_date'
    ];

    public function issue()
    {
        return $this->belongsTo('App\Issue');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
