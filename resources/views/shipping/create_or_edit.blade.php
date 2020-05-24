@extends('layouts.app')
@section('title', 'Shipping Option')

@section('content')
  @include('shared._error')
  @if ($opt->id)
    <form action="{{route('shipping.update', $opt->id)}}" method="POST">
      {{csrf_field()}}
      {{method_field('PUT')}}
      <input type="hidden" name="id" value="{{$opt->id}}" />
  @else
    <form action="{{route('shipping.store')}}" method="POST">
      {{csrf_field()}}
  @endif
    <div class="card">
      <h5 class="card-header">Shipping Option</h5>
      <div class="card-body">
        <div class="form-group">
          <label for="option">Option Name</label>
          <input type="text" class="form-control" id="option" name="option" value="{{old('option', $opt->option)}}" />
        </div>
      </div>
      <div class="card-footer row mx-0">
        <div class="col-6 text-right">
          <a href="{{route('shipping.index')}}" class="btn btn-outline-secondary" role="button">
            Cancel
          </a>
        </div>
        <div class="col-6 text-left">
          <button type="submit" class="btn btn-primary">
            Save
          </button>
        </div>
      </div>
    </div>
  </form>
@endsection
