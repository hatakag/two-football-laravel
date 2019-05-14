<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'user_id', 'time', 'type', 'amount', 'description'
    ];

    protected $attributes = [];

    protected $table = 'transaction';
    public $primaryKey = ['time', 'user_id'];
    public $timestamps = false;
    public $incrementing = false;
}
