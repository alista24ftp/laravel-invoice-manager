@extends('layouts.app')
@section('title', 'Payment Terms')

@section('content')
  <div class="card mt-3">
    <div class="card-header">
      <h5 class="d-inline-block">Payment Terms</h5>
      <a href="{{route('terms.create')}}" class="btn btn-primary d-inline-block ml-3" role="button">
        <i class="fas fa-plus"></i> Create Payment Term
      </a>
    </div>
    <div class="card-body">
      @include('terms._list', ['terms' => $terms])
      {!! $terms->appends(Request::except('page'))->render() !!}
    </div>
  </div>
@endsection
