<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bet extends ModelMPK
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

    public function end($betGain) {
        if ($betGain == 0)
            $this->bet_status = 'LOSE';
        else {
            $this->bet_gain = $betGain;
            $this->bet_status = 'WIN';
        }
        $this->save();
    }
}
