@extends('layouts.app')
@section('title', 'Edit Tax Info')

@section('content')
  @include('shared._error')
  <form action="{{route('taxes.update', $tax->id)}}" method="POST">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <input type="hidden" name="id" value="{{$tax->id}}" />

    <div class="card">
      <h5 class="card-header">Edit Tax Info</h5>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-4">
            <p>Province</p>
            <p>{{$tax->province}}</p>
          </div>
          <div class="form-group col-4">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" class="form-control"
              value="{{old('description', $tax->description)}}" />
          </div>
          <div class="form-group col-4">
            <label for="rate">Rate (%)</label>
            <input type="number" id="rate" name="rate" class="form-control"
              min="0" max="99.999" step="0.001" value="{{old('rate', $tax->rate)}}" />
          </div>
        </div>
      </div>
      <div class="card-footer row mx-0">
        <div class="col-6 text-right">
          <a href="{{route('taxes.index')}}" class="btn btn-outline-secondary" role="button">
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
