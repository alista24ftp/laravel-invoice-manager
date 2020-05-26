<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Customer extends Model
{
    protected $fillable = [
        'bill_name', 'bill_addr', 'bill_prov', 'bill_city', 'bill_postal',
        'ship_name', 'ship_addr', 'ship_prov', 'ship_city', 'ship_postal',
        'email', 'fax',
        'contact1_firstname', 'contact1_lastname', 'contact1_tel', 'contact1_cell',
        'contact2_firstname', 'contact2_lastname', 'contact2_tel', 'contact2_cell',
    ];

    public function scopeFindByKeyword($query, $keyword)
    {
        if(is_null($keyword) || $keyword == '') return $query;
        return $query->where('bill_name', 'like', "%$keyword%")
            ->orWhere('ship_name', 'like', "%$keyword%")
            ->orWhere('bill_postal', 'like', "%$keyword%")
            ->orWhere('ship_postal', 'like', "%$keyword%")
            ->orWhere('contact1_firstname', 'like', "%$keyword%")
            ->orWhere('contact2_firstname', 'like', "%$keyword%")
            ->orWhere('contact1_tel', 'like', "%$keyword%");
    }

    public function scopeWithOrder($query, $order)
    {
        if($order == 1) return $query->orderBy('bill_name', 'desc');
        if($order == 2) return $query->orderBy('ship_name');
        if($order == 3) return $query->orderBy('ship_name', 'desc');
        if($order == 4) return $query->orderBy('contact1_tel');
        if($order == 5) return $query->orderBy('contact1_tel', 'desc');
        return $query->orderBy('bill_name');
    }
}
