<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    protected $fillable = [
        'code', 'value'
    ];

    protected $attributes = [
        'active' => false,
    ];

    protected $table = 'card';
    public $primaryKey = 'code';
    public $timestamps = false;
    public $incrementing = false;
}
