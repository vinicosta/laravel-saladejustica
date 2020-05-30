<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{

    protected $table = 'reading';

    protected $fillable = [
        'title_id',
        'user_id'
    ];

    public function title()
    {
        return $this->belongsTo('App\Title');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
