<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    protected $table = 'card';
    public $primaryKey = 'code';
    public $timestamps = false;
}
