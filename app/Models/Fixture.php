<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    //
    protected $fillable = [
        'match_id', 'league_id', 'match_date', 'match_time', 'match_hometeam_name', 'match_awayteam_name', 'match_hometeam_halftime_score', 'match_awayteam_halftime_score', 'match_hometeam_score', 'match_awayteam_score', 'yellow_card'
    ];

    protected $attributes = [];

    protected $table = 'fixture';
    public $primaryKey = 'match_id';
    public $timestamps = false;
    public $incrementing = false;
}
