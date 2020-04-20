<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InvoiceFormRequest;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\SalesRep;
use App\Models\PaymentTerm;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Tax;

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Invoice $invoice)
    {
        // get params
        $date_to = isset($request->date_to) ? $request->date_to : null;
        $date_from = isset($request->date_from) ? $request->date_from : null;
        $sales_reps = SalesRep::all();
        $sales = isset($request->sales) ? $request->sales : '';
        $pay_status = isset($request->payment_status) ? $request->payment_status : 0;
        $invoice_no = isset($request->invoice_no) ? $request->invoice_no : null;
        $provinces = canadian_provinces();
        $prov = isset($request->cust_prov) ? $request->cust_prov : '';
        $cust_search = isset($request->cust_search) ? $request->cust_search : null;
        $order_by = isset($request->order_by) ? $request->order_by : 0;

        $invoices = $invoice->filterBetweenDates($date_from, $date_to)
            ->filterBySalesRep($sales)
            ->filterByPaid($pay_status)
            ->filterByInvoiceNo($invoice_no)
            ->filterByProv($prov)
            ->filterByCust($cust_search)
            ->withOrder($order_by)->with('orders')
            ->paginate(10);

        return view('invoices.index', compact('invoices',
            'date_to', 'date_from',
            'sales_reps', 'sales',
            'pay_status', 'invoice_no',
            'provinces', 'prov',
            'cust_search',
            'order_by'));
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function create(Invoice $invoice)
    {
        $provinces = canadian_provinces();
        $sales_reps = SalesRep::all();
        $payment_terms = PaymentTerm::all();
        $shipping_options = Shipping::all();
        $products = Product::all();
        $taxes = Tax::all();
        return view('invoices.create', compact('invoice',
            'provinces',
            'sales_reps',
            'payment_terms',
            'shipping_options',
            'products',
            'taxes'
        ));
    }

    public function store(InvoiceFormRequest $request, Invoice $invoice)
    {
        $invoice->fill($request->all());
        $invoice->save();
        $newOrders = [];
        foreach($request->orders as $order) {
            array_push($newOrders, new Order([
                'product' => $order['product'],
                'price' => $order['price'],
                'discount' => $order['discount'],
                'quantity' => $order['quantity'],
                'total' => $order['total'],
            ]));
        }
        $invoice->orders()->saveMany($newOrders);
        return redirect()->to(route('invoices.index'))->with('success', 'Invoice created successfully!');
    }

    public function edit(Invoice $invoice)
    {
        $provinces = canadian_provinces();
        $sales_reps = SalesRep::all();
        $payment_terms = PaymentTerm::all();
        $shipping_options = Shipping::all();
        $products = Product::all();
        $taxes = Tax::all();
        return view('invoices.edit', compact('invoice',
            'provinces',
            'sales_reps',
            'payment_terms',
            'shipping_options',
            'products',
            'taxes'
        ));
    }

    public function update(InvoiceFormRequest $request, Invoice $invoice)
    {
        $invoice->update($request->all());
        $invoice->orders()->delete();
        $newOrders = [];
        foreach($request->orders as $order) {
            array_push($newOrders, new Order([
                'product' => $order['product'],
                'price' => $order['price'],
                'discount' => $order['discount'],
                'quantity' => $order['quantity'],
                'total' => $order['total'],
            ]));
        }
        $invoice->orders()->saveMany($newOrders);
        return redirect()->to(route('invoices.index'))->with('success', 'Invoice updated successfully!');
    }

    public function duplicate(Invoice $invoice)
    {
        //return view('invoices.duplicate', compact('invoice'));
        return redirect()->route('invoices.create', [$invoice]);
    }

    // save invoice progress
    public function saveProgress(Request $request, Invoice $invoice)
    {
        if($request->isXmlHttpRequest()){
            $invoiceFields = $request->except(['_method']);
            // Cache
            if(Cache::put('user_' . Auth::user()->id . '_invoice', $invoiceFields)){
                return response('Invoice changes saved', 200);
            }
            // Unable to save invoice changes
            abort(500, 'Unable to save invoice changes');
        }
        return abort(404);
    }

    // restore saved invoice progress
    public function restoreProgress(Request $request, Invoice $invoice)
    {
        // Restore saved invoice from cache
        $invoiceFields = Cache::get('user_' . Auth::user()->id . '_invoice');
        if(!$invoiceFields){ // There is no previously saved invoice progress
            abort(404, 'There is no previously saved invoice progress');
            //return redirect()->route('invoices.index')->with('error', 'There is no previously saved invoice progress');
        }
        $invoice->fill($invoiceFields);
        foreach($invoiceFields['orders'] as $invoiceOrder){
            $invoice->orders->push($invoiceOrder);
        }
        if($invoiceFields['op'] == 'edit'){
            $invoice->invoice_no = $invoiceFields['old_invoice_no'];
            return redirect()->route('invoices.edit', [$invoice]);
        }
        return redirect()->route('invoices.create', [$invoice]);
    }

    // delete saved invoice progress
    public function deleteProgress(Request $request, Invoice $invoice)
    {
        if($request->isXmlHttpRequest()){
            // Delete saved invoice progress from cache
            $progress = Cache::forget('user_' . Auth::user()->id . '_invoice');
            if($progress){
                return response('Previous saved invoice progress deleted', 200);
            }
            abort(500, 'Unable to delete progress');
        }
        abort(404);
    }

    public function pay(Invoice $invoice)
    {
        return view('invoices.pay', compact('invoice'));
    }
}
