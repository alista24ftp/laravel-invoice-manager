@extends('layouts.app')
@section('title', 'Invoice View')

@section('content')
  <div class="container-fluid border">
    <div class="mt-3">
      <div class="row">
        <div class="col text-lg-center">
          <h1 class="text-wrap">{{strtoupper($invoice->company_name)}}</h1>
        </div>
      </div>
      <div class="row">
        <div class="col text-md-center">
          <p class="text-wrap">Mail Address: {{$invoice->company_mail_addr . ', ' . $invoice->company_mail_postal}}</p>
        </div>
      </div>
      <div class="row d-flex justify-content-center">
        <div class="col-5 text-center">
          <div class="text-wrap">CELL: {{$invoice->company_contact_cell ? $invoice->company_contact_cell : $invoice->company_contact_tel}} {{strtoupper($invoice->company_contact_fname)}}</div>
          <div class="text-wrap">TOLL FREE: {{$invoice->company_tollfree ? $invoice->company_tollfree : 'N/A'}}</div>
        </div>
        <div class="col-5 text-center">
          <div class="text-wrap">TEL: {{$invoice->company_tel}}</div>
          <div class="text-wrap">FAX: {{$invoice->company_fax ? $invoice->company_fax : 'N/A'}}</div>
        </div>
      </div>

      <div class="row d-flex justify-content-between mt-4">
        <div class="col-5 border">
          <p class="text-wrap">SOLD TO: {{strtoupper($invoice->bill_name)}}</p>
          <p class="text-wrap">{{strtoupper($invoice->bill_addr)}}</p>
          <p class="text-wrap">{{strtoupper($invoice->bill_city)}}, {{strtoupper($invoice->bill_prov)}}</p>
          <p class="text-wrap">{{strtoupper($invoice->bill_postal)}}</p>
        </div>
        <div class="col-5 border">
          <p class="text-wrap">SHIP TO: {{strtoupper($invoice->ship_name)}}</p>
          <p class="text-wrap">{{strtoupper($invoice->ship_addr)}}</p>
          <p class="text-wrap">{{strtoupper($invoice->ship_city)}}, {{strtoupper($invoice->ship_prov)}}</p>
          <p class="text-wrap">TEL: {{$invoice->customer_tel}} {{$invoice->customer_fax ? "FAX: $invoice->customer_fax" : ""}}</p>
          <p class="text-wrap">CONTACT: {{strtoupper($invoice->customer_contact1)}} {{$invoice->customer_contact2 ? "OR " . strtoupper($invoice->customer_contact2) : ""}}</p>
        </div>
      </div>

      <div class="row text-center m-3">
        <div class="col p-2">
          <h1>INVOICE # {{$invoice->invoice_no}}</h1>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="row">
            <div class="col-2 border">
              <p class="text-wrap">TAX REG. NO.</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">DATE</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">SALES REP</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">PO NO.</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">TERMS</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">VIA</p>
            </div>
          </div>
          <div class="row">
            <div class="col-2 border">
              <p class="text-wrap">{{$invoice->company_tax_reg}}</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">{{strtoupper($invoice->createDateFormat())}}</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">{{strtoupper($invoice->sales_rep)}}</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">{{$invoice->po_no ? strtoupper($invoice->po_no) : ''}}</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">{{$invoice->terms ? strtoupper($invoice->terms) : ''}}</p>
            </div>
            <div class="col-2 border">
              <p class="text-wrap">{{$invoice->via ? strtoupper($invoice->via) : ''}}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-2 border">
          <p class="text-wrap">QUANTITY</p>
        </div>
        <div class="col-4 border">
          <p class="text-wrap">DESCRIPTION</p>
        </div>
        <div class="col-2 border">
          <p class="text-wrap">PRICE</p>
        </div>
        <div class="col-2 border">
          <p class="text-wrap">DISCOUNT</p>
        </div>
        <div class="col-2 border">
          <p class="text-wrap">AMOUNT</p>
        </div>
      </div>
      @foreach($invoice->orders as $order_idx => $order)
        <div class="row">
          <div class="col-2 border-left border-right">
            <p class="text-wrap">{{$order->quantity}}</p>
          </div>
          <div class="col-4 border-right">
            <p class="text-wrap">{{strtoupper($order->product)}}</p>
          </div>
          <div class="col-2 border-right">
            <p class="text-wrap">{{'$'.number_format(floatval($order->price), 2)}}</p>
          </div>
          <div class="col-2 border-right">
            <p class="text-wrap">{{$order->discount ? '$'.number_format(floatval($order->discount), 2) : '$0.00'}}</p>
          </div>
          <div class="col-2 border-right">
            <p class="text-wrap">{{'$'.number_format(floatval($order->total), 2)}}</p>
          </div>
        </div>
      @endforeach
      <!-- Empty row -->
      <div class="row">
        <div class="col-2 border-left border-right">
          <p class="text-wrap"></p>
        </div>
        <div class="col-4 border-right">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right">
          <p class="text-wrap"></p>
        </div>
      </div>
      <!-- Amount -->
      <div class="row">
        <div class="col-2 border-left border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-4 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap">AMOUNT</p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap">{{'$'.number_format(round($invoice->orders->sum('total'), 2), 2)}}</p>
        </div>
      </div>
      <!-- Tax -->
      <div class="row">
        <div class="col-2 border-left border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-4 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap">
            {{$invoice->tax_rate.'%'}} {{strtoupper($invoice->tax_description)}}
          </p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap">{{'$'.number_format(round($invoice->orders->sum('total') * ($invoice->tax_rate / 100), 2), 2)}}</p>
        </div>
      </div>
      <!-- Freight -->
      <div class="row">
        <div class="col-2 border-left border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-4 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap">FREIGHT</p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap">{{$invoice->freight ? '$'.number_format(round($invoice->freight, 2), 2) : '$0.00'}}</p>
        </div>
      </div>
      <!-- Total Amount -->
      <div class="row">
        <div class="col-2 border-left border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-4 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap"></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap"><strong>TOTAL</strong></p>
        </div>
        <div class="col-2 border-right border-bottom">
          <p class="text-wrap"><strong>{{'$'.number_format($invoice->totalAmount(), 2)}}</strong></p>
        </div>
      </div>

      <!-- Footer -->
      <div class="row">
        <div class="col">
          <p class="text-wrap">E-mail: <u>{{$invoice->company_email}}</u></p>
        </div>
      </div>
      @if($invoice->company_website)
      <div class="row">
        <div class="col">
          <p class="text-wrap">Website: <u>{{$invoice->company_website}}</u></p>
        </div>
      </div>
      @endif
      <div class="row">
        <div class="col">
          <p class="text-wrap">Warehouse Address: {{$invoice->company_ware_addr . ', ' . $invoice->company_ware_postal}}</p>
        </div>
      </div>

      <!-- Options -->
      <div class="row mt-2 mb-2 d-flex justify-content-center">
        <div class="col-4 text-right">
          <a class="btn btn-secondary" href="{{route('invoices.index')}}">Back</a>
        </div>
        <div class="col-4 text-center">
          <a class="btn btn-outline-info" href="{{route('invoices.edit', $invoice->invoice_no)}}">Edit</a>
        </div>
        <div class="col-4 text-left">
          <a class="btn btn-info" href="{{route('invoices.getspreadsheet', $invoice->invoice_no)}}">Download Invoice</a>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('customstyles')
  <style>
    .text-wrap {
      overflow-wrap: break-word;
      white-space: normal !important;
    }
  </style>
@endsection
