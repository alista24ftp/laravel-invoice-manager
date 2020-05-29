<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'mail_addr', 'mail_postal',
        'warehouse_addr', 'warehouse_postal',
        'email', 'website', 'tax_reg', 'tel', 'fax', 'toll_free',
        'contact1_firstname', 'contact1_lastname', 'contact1_tel', 'contact1_ext', 'contact1_email', 'contact1_cell',
        'contact2_firstname', 'contact2_lastname', 'contact2_tel', 'contact2_ext', 'contact2_email', 'contact2_cell',
        'logo'
    ];

    protected $hidden = [];

    public $timestamps = false;
}
