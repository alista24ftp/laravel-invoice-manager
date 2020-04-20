<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesRep extends Model
{
    use SoftDeletes;

    protected $table = 'sales_reps';

    protected $fillable = [
        'firstname', 'lastname', 'tel', 'cell', 'email',
    ];

    protected $hidden = [
        'deleted_at',
    ];
}
