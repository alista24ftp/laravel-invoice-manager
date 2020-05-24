<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping';

    protected $fillable = [
        'option'
    ];

    public $timestamps = false;
}
