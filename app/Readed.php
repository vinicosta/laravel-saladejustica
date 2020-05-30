<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Readed extends Model
{

    protected $table = 'readed';

    protected $fillable = [
        'issue_id',
        'user_id',
        'readed_date'
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
