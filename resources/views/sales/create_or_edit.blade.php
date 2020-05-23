@extends('layouts.app')
@section('title', 'Sales Rep Info')

@section('content')
  @include('shared._error')
  @if($rep->id)
    <form id="sales_rep_form" method="POST" action="{{route('sales.update', $rep->id)}}">
      {{method_field('PUT')}}
      <input type="hidden" id="rep_id" name="id" value="{{$rep->id}}" />
  @else
    <form id="sales_rep_form" method="POST" action="{{route('sales.store')}}">
  @endif

    <div class="card">
      <h5 class="card-header">Sales Rep Info</h5>
      <div class="card-body">
        {{csrf_field()}}
        <div class="form-row">
          <div class="form-group col-3">
            <label for="firstname">First Name</label>
            <input type="text" class="form-control" id="firstname" name="firstname" maxlength="30"
              value="{{old('firstname', $rep->firstname)}}" />
          </div>
          <div class="form-group col-3">
            <label for="lastname">Last Name</label>
            <input type="text" class="form-control" id="lastname" name="lastname" maxlength="30"
              value="{{old('lastname', $rep->lastname)}}" />
          </div>
          <div class="form-group col-6">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="{{old('email', $rep->email)}}" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col">
            <label for="tel">Tel</label>
            <input type="text" class="form-control" id="tel" name="tel" maxlength="11" value="{{old('tel', $rep->tel)}}" />
          </div>
          <div class="form-group col">
            <label for="cell">Cell</label>
            <input type="text" class="form-control" id="cell" name="cell" maxlength="11"
              value="{{old('cell', $rep->cell)}}" />
          </div>
        </div>
      </div>
      <div class="card-footer row mx-0">
        <div class="col-6 text-right">
          <a href="{{route('sales.index')}}" class="btn btn-outline-secondary">Cancel</a>
        </div>
        <div class="col-6 text-left">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </form>
@endsection
