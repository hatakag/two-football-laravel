<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Comment extends ModelMPK
{
    //
    protected $fillable = [
        'match_id', 'user_id', 'time', 'comment'
    ];

    protected $table = 'comment';
    public $primaryKey = ['match_id', 'user_id', 'time'];
    public $timestamps = false;
    public $incrementing = false;
}
