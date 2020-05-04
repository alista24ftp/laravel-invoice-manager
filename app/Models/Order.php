<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Order extends Model
{
    protected $fillable = [
        'invoice_no', 'product', 'price', 'quantity', 'discount', 'total',
    ];

    public $timestamps = false; // removes 'created_at', 'updated_at' timestamps added by default

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_no', 'invoice_no');
    }
}
