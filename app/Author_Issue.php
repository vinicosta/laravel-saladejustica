<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author_Issue extends Model
{

    protected $table = 'author_issue';

    protected $fillable = [
        'issue_id', 'author_id'
    ];

}
