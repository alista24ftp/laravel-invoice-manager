@extends('layouts.app')
@section('title', 'Invoice Pay')

@section('content')
  <h1>Pay Invoice {{$invoice->invoice_no}}</h1>
@endsection
