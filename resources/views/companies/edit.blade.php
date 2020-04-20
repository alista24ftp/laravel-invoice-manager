@extends('layouts.app')
@section('title', 'Company Info')

@section('content')
  <div class="col-9">
    <h1>Company {{$company->company_name}}</h1>
  </div>
@endsection
