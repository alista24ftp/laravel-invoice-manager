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
use App\Models\Company;
use App\Models\PaymentProof;
use App\Traits\FilesystemTrait;
use App\Handlers\SpreadsheetHandler;

class InvoicesController extends Controller
{
    use FilesystemTrait;

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
            ->withOrder($order_by)
            ->with('orders')
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
        $company = Company::find(1);

        if(session()->has('saved_invoice')){
            $invoice = session('saved_invoice');
            session()->remove('saved_invoice');
        }
        return view('invoices.create', compact('invoice',
            'provinces',
            'sales_reps',
            'payment_terms',
            'shipping_options',
            'products',
            'taxes',
            'company'
        ));
    }

    public function store(InvoiceFormRequest $request, Invoice $invoice)
    {
        $invoice->fill($request->all());
        if(!isset($request->paid)){
            $invoice->paid = 0;
        }
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

        $msg_type = 'success';
        $msg = 'Invoice created successfully!';

        // save related payment proofs to database and move related temp proofs to payment_proofs directory
        if(is_array($request->proofs) && count($request->proofs) > 0){
            $newProofs = [];
            $numProofs = count($request->proofs);
            $successProofs = 0;
            $dest_dir = '/uploads/images/payment_proofs/' . date('Ym/d', time()); // eg. /uploads/images/payment_proofs/202004/13
            $dest_path = public_path() . $dest_dir;
            if(!file_exists($dest_path) || !is_dir($dest_path)){
                mkdir($dest_path, 0777, true);
            }
            foreach($request->proofs as $proof){ // eg. proof -> /uploads/images/temp/1234567890_a1B2c3D4e5.png
                $old_proof_path = public_path() . $proof;
                if(file_exists($old_proof_path)){
                    $proof_filename = pathinfo($old_proof_path, PATHINFO_BASENAME); // eg. 1234567890_a1B2c3D4e5.png
                    $new_proof_name = $dest_dir . '/'. $invoice->invoice_no . '_' . $proof_filename; // eg. /uploads/images/payment_proofs/202004/13/1_1234567890_a1B2c3D4e5.png
                    if(rename($old_proof_path, public_path() . $new_proof_name)){
                        array_push($newProofs, new PaymentProof([
                            'path' => $new_proof_name,
                            'create_time' => now()
                        ]));
                        $successProofs++;
                    }
                }
            }
            $invoice->paymentProofs()->saveMany($newProofs);
            if($successProofs != $numProofs){
                $msg_type = 'warning';
                $msg = 'Invoice created successfully, but problems occurred when uploading payment proofs';
            }
        }
        return redirect()->to(route('invoices.index'))->with($msg_type, $msg);
    }

    public function edit(Invoice $invoice)
    {
        $provinces = canadian_provinces();
        $sales_reps = SalesRep::all();
        $payment_terms = PaymentTerm::all();
        $shipping_options = Shipping::all();
        $products = Product::all();
        $taxes = Tax::all();
        $company = Company::find(1);

        if(session()->has('saved_invoice')){
            $invoice = session('saved_invoice');
            session()->remove('saved_invoice');
        }
        return view('invoices.edit', compact('invoice',
            'provinces',
            'sales_reps',
            'payment_terms',
            'shipping_options',
            'products',
            'taxes',
            'company'
        ));
    }

    public function update(InvoiceFormRequest $request, Invoice $invoice)
    {
        if(!isset($request->paid)){
            $invoice->paid = 0;
        }
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
            if(!isset($request->paid)){
                $invoiceFields['paid'] = 0;
            }
            // Cache
            $cache_key = 'user_' . Auth::user()->id . '_invoice';
            // Remove any previously saved invoice
            if(Cache::has($cache_key)){ // if cache has previously saved invoice
                $prevFields = Cache::get($cache_key);
                if(array_key_exists('proofs', $prevFields)){ // prev saved invoice contains payment proofs
                    // remove all prev saved invoice payment proofs
                    foreach($prevFields['proofs'] as $proof) { // proof['path'] -> /uploads/images/saved/1234567890_abcdefghij.png
                        $proof_full_path = public_path() . $proof['path'];
                        if(file_exists($proof_full_path) && is_file($proof_full_path)){
                            unlink($proof_full_path);
                        }
                    }
                }
                Cache::forget($cache_key); // remove previously saved invoice
            }

            // Save current invoice state
            $res = ['status' => 1, 'msg' => 'Invoice changes saved successfully'];
            // 1. Save and update any related invoice payment proofs
            if(array_key_exists('proofs', $invoiceFields)) {
                // move temp proofs to new location
                $proof_files = array_map(function($proof){
                    return $proof['path'];
                }, $invoiceFields['proofs']);
                $move_result = $this->moveFilesToDir($proof_files, '/uploads/images/saved');
                $invoiceFields['proofs'] = [];
                $res['proofs'] = [];
                foreach($move_result['newPaths'] as $new_path){
                    // replace original proof path with new proof path
                    array_push($invoiceFields['proofs'], ['path' => $new_path['path']]);
                    array_push($res['proofs'], $new_path); // return updated proof paths with response
                }
                if(count($move_result['failedPaths']) > 0){ // return any failed proof paths with response
                    $res['status'] = 2;
                    $res['msg'] = 'Invoice changes saved, but some proofs were not saved';
                    $res['failed_proofs'] = [];
                    foreach($move_result['failedPaths'] as $failed_path){
                        array_push($res['failed_proofs'], $failed_path);
                    }
                }
            }
            // 2. Save invoice state to cache
            if(Cache::put($cache_key, $invoiceFields)){
                return response($res, 200);
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
        $cache_key = 'user_' . Auth::user()->id . '_invoice';
        $invoiceFields = Cache::get($cache_key);
        if(!$invoiceFields){ // There is no previously saved invoice progress
            abort(404, 'There is no previously saved invoice progress');
            //return redirect()->route('invoices.index')->with('error', 'There is no previously saved invoice progress');
        }
        $invoice->fill($invoiceFields);
        Cache::forget($cache_key); // Remove from cache once restored

        // restore invoice orders, if any
        if(array_key_exists('orders', $invoiceFields)){
            $invoice->orders = collect([]);
            foreach($invoiceFields['orders'] as $invoiceOrder){
                $invoice->orders->push(new Order($invoiceOrder));
            }
        }

        if($invoiceFields['op'] == 'edit'){
            $invoice->invoice_no = $invoiceFields['old_invoice_no'];
            session(['saved_invoice' => $invoice]);
            return redirect()->route('invoices.edit', [$invoice]);
        }
        // restore create invoice form
        // restore invoice payment proofs, if any
        if(array_key_exists('proofs', $invoiceFields)){
            $invoice->paymentProofs = collect([]);
            $saved_proofs = [];
            foreach($invoiceFields['proofs'] as $invoiceProof){
                array_push($saved_proofs, $invoiceProof['path']);
            }
            // when restoring proofs, move them back to temp dir
            $move_result = $this->moveFilesToDir($saved_proofs, '/uploads/images/temp');
            foreach($move_result['newPaths'] as $new_path){
                $invoice->paymentProofs->push(new PaymentProof(['path' => $new_path['path']]));
            }
        }
        session(['saved_invoice' => $invoice]);
        return redirect()->route('invoices.create');
    }

    // delete saved invoice progress
    public function deleteProgress(Request $request, Invoice $invoice)
    {
        if($request->isXmlHttpRequest()){
            // Delete saved invoice progress from cache
            $cache_key = 'user_' . Auth::user()->id . '_invoice';
            if(Cache::has($cache_key)){
                $invoiceFields = Cache::get($cache_key);
                // remove saved invoice payment proofs, if any
                if(array_key_exists('proofs', $invoiceFields)){
                    foreach($invoiceFields['proofs'] as $proof){
                        $proof_full_path = public_path() . $proof['path'];
                        if(file_exists($proof_full_path) && is_file($proof_full_path)){
                            unlink($proof_full_path);
                        }
                    }
                }
            }
            $progress = Cache::forget($cache_key);
            if($progress){
                return response('Previous saved invoice progress deleted', 200);
            }
            return abort(500, 'Unable to delete saved invoice progress');
        }
        abort(404);
    }

    // Create and return invoice spreadsheet
    public function getSpreadsheet(Invoice $invoice, SpreadsheetHandler $handler)
    {
        $handler->outputExcelInvoice($invoice);
    }
}
