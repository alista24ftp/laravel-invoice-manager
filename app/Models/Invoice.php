<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Company;
use App\Models\Customer;
use Carbon\Carbon;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_no';

    protected $fillable = [
        'invoice_no', 'create_date', 'sales_rep', 'po_no', 'terms', 'via', 'memo', 'notes', 'paid',
        'company_id',
        'company_name', 'company_mail_addr', 'company_mail_postal',
        'company_email', 'company_website',
        'company_ware_addr', 'company_ware_postal',
        'company_tel', 'company_fax', 'company_tollfree',
        'company_contact_fname', 'company_contact_lname',
        'company_contact_tel', 'company_contact_cell', 'company_contact_email',
        'company_tax_reg',
        'customer_id',
        'bill_name', 'bill_addr', 'bill_prov', 'bill_city', 'bill_postal',
        'ship_name', 'ship_addr', 'ship_prov', 'ship_city', 'ship_postal',
        'customer_tel', 'customer_fax', 'customer_contact1', 'customer_contact2',
        'tax_description', 'tax_rate', 'freight',
    ];

    /**
     * filterBetweenDates($date_to, $date_from)
     * filterBySalesRep($sales)
     * filterByPaid($pay_status)
     * filterByInvoiceNo($invoice_no)
     * filterByProv($prov)
     * filterByCust($cust_search)
     */
    public function scopeFilterBetweenDates($query, $date_from, $date_to)
    {
        if(is_null($date_from) && is_null($date_to)) return $query;
        if(is_null($date_from)) return $query->where('create_date', '<=', $date_to);
        if(is_null($date_to)) return $query->where('create_date', '>=', $date_from);
        return $query->where('create_date', '>=', $date_from)->where('create_date', '<=', $date_to);
    }

    public function scopeFilterBySalesRep($query, $sales)
    {
        if(is_null($sales) || $sales == '') return $query;
        if($sales == 'None') return $query->whereNull('sales_rep');
        return $query->where('sales_rep', $sales);
    }

    public function scopeFilterByPaid($query, $pay_status)
    {
        if($pay_status == 1) return $query->where('paid', true);
        if($pay_status == 2) return $query->where('paid', false);
        return $query;
    }

    public function scopeFilterByInvoiceNo($query, $invoice_no)
    {
        if(is_null($invoice_no) || $invoice_no == '') return $query;
        return $query->where('invoice_no', 'like', "%$invoice_no%");
    }

    public function scopeFilterByProv($query, $prov)
    {
        if(is_null($prov) || $prov == '') return $query;
        return $query->where('bill_prov', $prov)->orWhere('ship_prov', $prov);
    }

    public function scopeFilterByCust($query, $cust_search)
    {
        if(is_null($cust_search) || $cust_search == '') return $query;
        //$cust_search = strtoupper($cust_search);
        return $query->where('bill_name', 'like', "%$cust_search%")
            ->orWhere('ship_name', 'like', "%$cust_search%")
            ->orWhere('customer_tel', 'like', "%$cust_search%")
            ->orWhere('customer_contact1', 'like', "%$cust_search%");
    }

    public function scopeWithOrder($query, $order)
    {
        if($order == 1) return $query->orderBy('create_date');
        if($order == 2) return $query->orderBy('bill_name');
        if($order == 3) return $query->orderBy('bill_name', 'desc');
        if($order == 4) return $query->orderBy('sales_rep');
        if($order == 5) return $query->orderBy('sales_rep', 'desc');
        return $query->orderBy('create_date', 'desc');
    }

    public function totalAmount()
    {
        return round($this->orders->sum('total') * (1 + $this->tax_rate / 100) + $this->freight, 2);
    }

    public function overdue()
    {
        if($this->paid) return false;

        $term = $this->terms;
        $create_date = Carbon::createFromFormat('Y-m-d', $this->create_date);
        $elapsed_days = $create_date->diffInDays(Carbon::now());

        if($term == '15DAYS'){
            return $elapsed_days > 15;
        }
        if($term == '30DAYS'){
            return $elapsed_days > 30;
        }
        if($term == '60DAYS'){
            return $elapsed_days > 60;
        }
        return false;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'invoice_no', 'invoice_no');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
