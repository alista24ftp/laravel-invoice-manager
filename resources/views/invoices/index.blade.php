@extends('layouts.app')
@section('title', 'Invoices')

@section('content')

      <div class="m-1 p-1">
          <h3 class="m-3 bg-primary text-white text-center">Invoices</h3>
          <form action="{{route('invoices.index')}}" method="get">
            <div class="card">
              <div class="card-body border">
                <div class="row">
                  <div class="col-3">
                      <div class="form-group">
                          <label for="date_from">From Date</label>
                          <input type="date" id="date_from" name="date_from" class="form-control" value="{{$date_from}}" />
                      </div>
                  </div>
                  <div class="col-3">
                      <div class="form-group">
                          <label for="date_to">To Date</label>
                          <input type="date" id="date_to" name="date_to" class="form-control" value="{{$date_to}}" />
                      </div>
                  </div>
                  <div class="col-3">
                      <div class="form-group">
                          <label for="sales">Sales Rep</label>
                          <select id="sales" name="sales" class="form-control">
                              <option value="" {{ $sales == '' ? 'selected' : '' }}>All</option>
                              <option value="None" {{ $sales == 'None' ? 'selected' : '' }}>None</option>
                              @foreach($sales_reps as $rep_id => $rep)
                                <option value="{{ $rep->firstname . ' ' . $rep->lastname }}" {{ $sales == ($rep->firstname . ' ' . $rep->lastname) ? 'selected' : '' }}>
                                  {{ $rep->firstname . ' ' . $rep->lastname }}
                                </option>
                              @endforeach

                          </select>
                      </div>
                  </div>
                  <div class="col-3">
                      <div class="form-group">
                          <label for="payment_status">Payment Status</label>
                          <select id="payment_status" name="payment_status" class="form-control">
                              <option value="0" {{$pay_status == 0 ? 'selected' : ''}}>All</option>
                              <option value="1" {{$pay_status == 1 ? 'selected' : ''}}>Paid</option>
                              <option value="2" {{$pay_status == 2 ? 'selected' : ''}}>Not Paid</option>
                          </select>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-4">
                      <div class="form-group">
                          <label for="invoice_no">Invoice #</label>
                          <input type="text" id="invoice_no" name="invoice_no" class="form-control" value="{{$invoice_no}}" />
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="form-group">
                          <label for="cust_prov">Province</label>
                          <select id="cust_prov" name="cust_prov" class="form-control">
                              <option value="" {{$prov == '' ? 'selected' : ''}}>Select Province</option>
                              @foreach($provinces as $abbr => $prov_name)
                                <option value="{{$abbr}}" {{$prov == $abbr ? 'selected' : ''}}>{{$prov_name}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="form-group">
                          <label for="cust_search">Customer Search</label>
                          <input type="text" id="cust_search" name="cust_search" placeholder="" class="form-control" value="{{$cust_search}}" />
                      </div>
                  </div>
                </div>
              </div>
              <div class="card-body border">
                  <div class="col">
                      <div class="form-group">
                          <label for="order_by">Order By</label>
                          <select id="order_by" name="order_by" class="form-control">
                              <option value="0" {{$order_by == 0 ? 'selected' : ''}}>Most Recent</option>
                              <option value="1" {{$order_by == 1 ? 'selected' : ''}}>Least Recent</option>
                              <option value="2" {{$order_by == 2 ? 'selected' : ''}}>Bill To A-Z</option>
                              <option value="3" {{$order_by == 3 ? 'selected' : ''}}>Bill To Z-A</option>
                              <option value="4" {{$order_by == 4 ? 'selected' : ''}}>Sales Rep A-Z</option>
                              <option value="5" {{$order_by == 5 ? 'selected' : ''}}>Sales Rep Z-A</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="card-body border">
                <button class="btn btn-outline-secondary" type="submit">Filter</button>
                <a href="{{route('invoices.create')}}" class="btn btn-primary">Create Invoice</a>
              </div>
            </div>
          </form>


          @include('invoices._invoice_list', ['invoices' => $invoices])

          <div class="row text-center">
            {!! $invoices->appends(Request::except('page'))->render() !!}
          </div>
      </div>

@endsection
