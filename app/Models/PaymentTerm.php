<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $table = 'terms';

    protected $fillable = [
        'option',
    ];
}
