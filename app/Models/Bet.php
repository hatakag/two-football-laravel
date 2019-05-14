<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    //
    protected $fillable = [
        'user_id', 'match_id', 'bet_type', 'bet_amount', 'bet_content', 'bet_time'
    ];

    protected $attributes = [
        'bet_status' => 'PROCESSING',
        'bet_gain' => 0,
    ];

    protected $table = 'bet';
    public $primaryKey = ['user_id', 'match_id', 'bet_type'];
    public $timestamps = false;
    public $incrementing = false;
}
