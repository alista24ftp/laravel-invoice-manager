@extends('layouts.app')
@section('title', 'Shipping Options')

@section('content')
  <div class="card">
    <h5 class="card-header">
      Shipping Options
    </h5>
    <div class="card-body">
      <a href="{{route('shipping.create')}}" class="btn btn-primary mb-2" role="button">
        <i class="fas fa-plus"></i> Create Option
      </a>
      @include('shipping._options_list', ['options' => $options])

      {!! $options->appends(Request::except('page'))->render() !!}
    </div>
  </div>
@endsection
