@extends('layouts.app')
@section('title', ($customer->id ? 'Customer Edit' : 'Customer Create'))

@section('content')
  <div class="col-9">
    <h3 class="bg-primary text-white text-center">
      @if ($customer->id)
        Customer Edit
      @else
        Customer Create
      @endif
    </h3>
    @if($customer->id)
      <form action="{{route('customers.update', $customer->id)}}" method="POST">
      <input type="hidden" name="_method" value="PATCH">
      <input type="hidden" id="id" name="id" value="{{$customer->id}}">
    @else
      <form action="{{route('customers.store')}}" method="POST">
    @endif
      <input type="hidden" name="_token" value="{{csrf_token()}}">

      @include('shared._error')

      <div class="form-row">
        <div class="col-6">
          <div class="form-group">
            <label for="bill_name">Bill To</label>
            <input id="bill_name" name="bill_name" type="text" class="form-control"
              value="{{old('bill_name', $customer->bill_name)}}" required>
          </div>
          <div class="form-group">
            <label for="bill_addr">Address</label>
            <input type="text" id="bill_addr" name="bill_addr" class="form-control"
              value="{{old('bill_addr', $customer->bill_addr)}}" required>
          </div>
          <div class="form-group">
            <label for="bill_prov">Province</label>
            <select id="bill_prov" name="bill_prov" class="form-control" required>
              <option value="" hidden disabled {{$customer->id ? '' : 'selected'}}>Select Province</option>
              @foreach($provinces as $abbr => $prov)
                <option value="{{$abbr}}" {{$customer->bill_prov == $abbr ? 'selected' : ''}}>
                  {{$abbr}} - {{$prov}}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="bill_city">City</label>
            <input type="text" id="bill_city" name="bill_city" class="form-control"
              value="{{old('bill_city', $customer->bill_city)}}" required>
          </div>
          <div class="form-group">
            <label for="bill_postal">Postal Code</label>
            <input id="bill_postal" name="bill_postal" type="text" class="form-control"
              value="{{old('bill_postal', $customer->bill_postal)}}" maxlength="6" required>
          </div>
        </div>

        <div class="col-6">
          <div class="form-group">
            <label for="ship_name">Ship To</label>
            <input id="ship_name" name="ship_name" type="text" class="form-control"
              value="{{old('ship_name', $customer->ship_name)}}" required>
          </div>
          <div class="form-group">
            <label for="ship_addr">Address</label>
            <input type="text" id="ship_addr" name="ship_addr" class="form-control"
              value="{{old('ship_addr', $customer->ship_addr)}}" required>
          </div>
          <div class="form-group">
            <label for="ship_prov">Province</label>
            <select id="ship_prov" name="ship_prov" class="form-control" required>
              <option value="" hidden disabled {{$customer->id ? '' : 'selected'}}>Select Province</option>
              @foreach($provinces as $abbr => $prov)
                <option value="{{$abbr}}" {{$customer->ship_prov == $abbr ? 'selected' : ''}}>
                  {{$abbr}} - {{$prov}}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="ship_city">City</label>
            <input type="text" id="ship_city" name="ship_city" class="form-control"
              value="{{old('ship_city', $customer->ship_city)}}" required>
          </div>
          <div class="form-group">
            <label for="ship_postal">Postal Code</label>
            <input id="ship_postal" name="ship_postal" type="text" class="form-control"
              value="{{old('ship_postal', $customer->ship_postal)}}" maxlength="6" required>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col">
          <label for="email">Customer Email</label>
          <input type="text" id="email" name="email" class="form-control"
            value="{{old('email', $customer->email)}}" />
        </div>
        <div class="form-group col">
          <label for="fax">Customer Fax</label>
          <input type="text" id="fax" name="fax" class="form-control"
            value="{{old('fax', $customer->fax)}}" maxlength="11" />
        </div>
      </div>

      <div class="card-group">
        <div class="card">
          <h5 class="card-header">
            Contact 1
          </h5>
          <div class="card-body">
            <div class="form-group">
              <label for="contact1_firstname">First Name</label>
              <input type="text" id="contact1_firstname" name="contact1_firstname" class="form-control"
                value="{{old('contact1_firstname', $customer->contact1_firstname)}}" maxlength="30" required>
            </div>
            <div class="form-group">
              <label for="contact1_lastname">Last Name</label>
              <input type="text" id="contact1_lastname" name="contact1_lastname" class="form-control"
                value="{{old('contact1_lastname', $customer->contact1_lastname)}}" maxlength="30">
            </div>
            <div class="form-group">
              <label for="contact1_tel">Telephone</label>
              <input type="text" id="contact1_tel" name="contact1_tel" class="form-control"
                value="{{old('contact1_tel', $customer->contact1_tel)}}" maxlength="11" required>
            </div>
            <div class="form-group">
              <label for="contact1_cell">Cell Phone</label>
              <input type="text" id="contact1_cell" name="contact1_cell" class="form-control"
                value="{{old('contact1_cell', $customer->contact1_cell)}}" maxlength="11">
            </div>
          </div>
        </div>

        <div class="card">
          <h5 class="card-header">
            Contact 2
          </h5>
          <div class="card-body">
            <div class="form-group">
              <label for="contact2_firstname">First Name</label>
              <input type="text" id="contact2_firstname" name="contact2_firstname" class="form-control"
                value="{{old('contact2_firstname', $customer->contact2_firstname)}}" maxlength="30">
            </div>
            <div class="form-group">
              <label for="contact2_lastname">Last Name</label>
              <input type="text" id="contact2_lastname" name="contact2_lastname" class="form-control"
                value="{{old('contact2_lastname', $customer->contact2_lastname)}}" maxlength="30">
            </div>
            <div class="form-group">
              <label for="contact2_tel">Telephone</label>
              <input type="text" id="contact2_tel" name="contact2_tel" class="form-control"
                value="{{old('contact2_tel', $customer->contact2_tel)}}" maxlength="11">
            </div>
            <div class="form-group">
              <label for="contact2_cell">Cell Phone</label>
              <input type="text" id="contact2_cell" name="contact2_cell" class="form-control"
                value="{{old('contact2_cell', $customer->contact2_cell)}}" maxlength="11">
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary btn-submit">Save</button>
        <a class="btn btn-outline-secondary" href="{{route('customers.index')}}">Cancel</a>
      </div>
    </form>
  </div>
@endsection
