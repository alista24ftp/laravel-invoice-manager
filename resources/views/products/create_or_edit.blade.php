@extends('layouts.app')
@section('title', 'Product Info')

@section('content')
  @include('shared._error')
  @if ($product->id)
    <form action="{{route('products.update', $product->id)}}" method="POST"
      onsubmit="return confirm('Are you sure you want to save changes?');">
      {{csrf_field()}}
      {{method_field('PUT')}}
      <input type="hidden" name="id" value="{{$product->id}}" />
  @else
    <form action="{{route('products.store')}}" method="POST"
      onsubmit="return confirm('Are you sure you want to save changes?');">
      {{csrf_field()}}
  @endif
    <div class="card">
      <h5 class="card-header">
        Product Info
      </h5>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{old('name', $product->name)}}" />
          </div>
          <div class="form-group col">
            <label for="price">Product Price</label>
            <input type="number" class="form-control" id="price" name="price"
              min="0" max="10000" step="0.01"
              value="{{old('price', $product->price ? number_format($product->price, 2) : 0)}}" />
          </div>
        </div>
      </div>
      <div class="card-footer row mx-0">
        <div class="col-6 text-right">
          <a href="{{route('products.index')}}" class="btn btn-outline-secondary" role="button">
            Cancel
          </a>
        </div>
        <div class="col-6 text-left">
          <button type="submit" class="btn btn-primary">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </form>
@endsection
