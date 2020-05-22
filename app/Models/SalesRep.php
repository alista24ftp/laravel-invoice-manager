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

    public function scopeFilterByName($query, $name)
    {
        if(is_null($name) || $name == '') return $query;
        return $query->where('firstname', 'like', "%$name%")->orWhere('lastname', 'like', "%$name%");
    }

    public function scopeFilterByPhone($query, $phone)
    {
        if(is_null($phone) || $phone == '') return $query;
        return $query->where('tel', 'like', "%$phone%")->orWhere('cell', 'like', "%$phone%");
    }

    public function scopeFilterByEmail($query, $email)
    {
        if(is_null($email) || $email == '') return $query;
        return $query->where('email', 'like', "%$email%");
    }

    public function scopeFilterByStatus($query, $status)
    {
        if($status == 1) return $query; // query by default doesn't include soft deleted entries
        if($status == 2) return $query->onlyTrashed(); // only include soft deleted entries
        return $query->withTrashed(); // include soft deleted entries
    }

    public function scopeWithOrder($query, $order_by)
    {
        if($order_by == 1) return $query->orderBy('firstname', 'desc');
        if($order_by == 2) return $query->orderBy('lastname');
        if($order_by == 3) return $query->orderBy('lastname', 'desc');
        return $query->orderBy('firstname');
    }
}
