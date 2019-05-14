<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    //
    protected $fillable = [
        'league_id', 'league_name', 'country'
    ];

    protected $attributes = [];

    protected $table = 'league';
    public $primaryKey = 'league_id';
    public $timestamps = false;
    public $incrementing = false;
}
