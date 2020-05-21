@extends('layouts.app')
@section('title', 'Company Info')

@section('content')
  <div>
    <h1 class="h1 text-center text-white bg-info">Company Info</h1>
    <form action="{{route('companies.update', $company->id)}}" id="company_form" method="POST">
      @include('shared._error')
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      <input type="hidden" name="id" id="company_id" value="{{old('id', $company->id)}}" />

      <div class="card">
        <div class="card-header">
          <div class="form-group">
            <label for="company_name">Company Name</label>
            <input class="form-control" id="company_name" name="company_name" type="text"
              value="{{old('company_name', $company->company_name)}}" />
          </div>
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-9">
              <label for="mail_addr">Mailing Address</label>
              <input type="text" class="form-control" id="mail_addr" name="mail_addr"
                value="{{old('mail_addr', $company->mail_addr)}}" />
            </div>
            <div class="form-group col-3">
              <label for="mail_postal">Postal Code</label>
              <input type="text" class="form-control" maxlength="6" id="mail_postal" name="mail_postal"
                value="{{old('mail_postal', $company->mail_postal)}}" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-9">
              <label for="warehouse_addr">Warehouse Address</label>
              <input type="text" class="form-control" id="warehouse_addr" name="warehouse_addr"
                value="{{old('warehouse_addr', $company->warehouse_addr)}}" />
            </div>
            <div class="form-group col-3">
              <label for="warehouse_postal">Postal Code</label>
              <input type="text" class="form-control" maxlength="6" id="warehouse_postal" name="warehouse_postal"
                value="{{old('warehouse_postal', $company->warehouse_postal)}}" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-4">
              <label for="email">Email</label>
              <input type="text" class="form-control" id="email" name="email" value="{{old('email', $company->email)}}" />
            </div>
            <div class="form-group col-4">
              <label for="website">Website</label>
              <input type="text" class="form-control" id="website" name="website"
                value="{{old('website', $company->website)}}" />
            </div>
            <div class="form-group col-4">
              <label for="tax_reg">Tax Reg. No.</label>
              <input type="text" class="form-control" id="tax_reg" name="tax_reg"
                value="{{old('tax_reg', $company->tax_reg)}}" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-4">
              <label for="tel">Company Tel</label>
              <input type="text" class="form-control" id="tel" name="tel" value="{{old('tel', $company->tel)}}" />
            </div>
            <div class="form-group col-4">
              <label for="fax">Company Fax</label>
              <input type="text" class="form-control" id="fax" name="fax" value="{{old('fax', $company->fax)}}" />
            </div>
            <div class="form-group col-4">
              <label for="toll_free">Toll Free</label>
              <input type="text" class="form-control" id="toll_free" name="toll_free"
                value="{{old('toll_free', $company->toll_free)}}" />
            </div>
          </div>
          <h5 class="h5">Contact 1 Info</h5>
          <div class="form-row">
            <div class="form-group col-4">
              <label for="contact1_firstname">First Name</label>
              <input type="text" class="form-control" id="contact1_firstname" name="contact1_firstname"
                value="{{old('contact1_firstname', $company->contact1_firstname)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact1_lastname">Last Name</label>
              <input type="text" class="form-control" id="contact1_lastname" name="contact1_lastname"
                value="{{old('contact1_lastname', $company->contact1_lastname)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact1_email">Email</label>
              <input type="text" class="form-control" id="contact1_email" name="contact1_email"
                value="{{old('contact1_email', $company->contact1_email)}}" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-4">
              <label for="contact1_tel">Tel</label>
              <input type="text" class="form-control" id="contact1_tel" name="contact1_tel"
                value="{{old('contact1_tel', $company->contact1_tel)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact1_ext">Ext.</label>
              <input type="text" class="form-control" id="contact1_ext" maxlength="6" name="contact1_ext"
                value="{{old('contact1_ext', $company->contact1_ext)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact1_cell">Cell</label>
              <input type="text" class="form-control" id="contact1_cell" name="contact1_cell"
                value="{{old('contact1_cell', $company->contact1_cell)}}" />
            </div>
          </div>
          <h5 class="h5">Contact 2 Info</h5>
          <div class="form-row">
            <div class="form-group col-4">
              <label for="contact2_firstname">First Name</label>
              <input type="text" class="form-control" id="contact2_firstname" name="contact2_firstname"
                value="{{old('contact2_firstname', $company->contact2_firstname)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact2_lastname">Last Name</label>
              <input type="text" class="form-control" id="contact2_lastname" name="contact2_lastname"
                value="{{old('contact2_lastname', $company->contact2_lastname)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact2_email">Email</label>
              <input type="text" class="form-control" id="contact2_email" name="contact2_email"
                value="{{old('contact2_email', $company->contact2_email)}}" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-4">
              <label for="contact2_tel">Tel</label>
              <input type="text" class="form-control" id="contact2_tel" name="contact2_tel"
                value="{{old('contact2_tel', $company->contact2_tel)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact2_ext">Ext.</label>
              <input type="text" class="form-control" id="contact2_ext" maxlength="6" name="contact2_ext"
                value="{{old('contact2_ext', $company->contact2_ext)}}" />
            </div>
            <div class="form-group col-4">
              <label for="contact2_cell">Cell</label>
              <input type="text" class="form-control" id="contact2_cell" name="contact2_cell"
                value="{{old('contact2_cell', $company->contact2_cell)}}" />
            </div>
          </div>
        </div>
        <div class="card-footer text-center">
          <button type="submit" class="btn btn-primary">Update Info</button>
        </div>
      </div>
    </form>
  </div>
@endsection
