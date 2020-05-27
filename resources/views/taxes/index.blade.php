@extends('layouts.app')
@section('title', 'Taxes')

@section('content')
  <div class="card">
    <h5 class="card-header">Taxes</h5>
    <div class="card-body">
      @include('taxes._list', ['taxes' => $taxes])
    </div>
  </div>
@endsection
