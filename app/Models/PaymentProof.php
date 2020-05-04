<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    protected $table = 'payment_proofs';

    protected $fillable = [
        'invoice_no', 'path', 'create_time'
    ];

    public $timestamps = false; // removes 'created_at', 'updated_at' timestamps added by default

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_no', 'invoice_no');
    }
}
