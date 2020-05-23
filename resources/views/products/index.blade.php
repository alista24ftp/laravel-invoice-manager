@extends('layouts.app')
@section('title', 'Products')

@section('content')
  <h1 class="h1 bg-info text-center text-white p-2">Products</h1>
  <form action="{{route('products.index')}}" method="GET">
    <div class="card">
      <h5 class="card-header">
        Filter/Order By
      </h5>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-3">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{$name}}" />
          </div>
          <div class="form-group col-3">
            <label for="min_price">Low Price</label>
            <input type="number" class="form-control" id="min_price" name="min_price"
              min="0" max="10000" step="0.01" value="{{is_null($min_price) ? 0 : $min_price}}" />
          </div>
          <div class="form-group col-3">
            <label for="max_price">High Price</label>
            <input type="number" class="form-control" id="max_price" name="max_price"
              min="0" max="10000" step="0.01" value="{{is_null($max_price) ? 10000 : $max_price}}" />
          </div>
          <div class="form-group col-3">
            <label for="order_by">Order By</label>
            <select id="order_by" name="order_by" class="form-control">
              <option value="0" {{$order_by == 0 ? 'selected' : ''}}>Product ID</option>
              <option value="1" {{$order_by == 1 ? 'selected' : ''}}>Product Name A-Z</option>
              <option value="2" {{$order_by == 2 ? 'selected' : ''}}>Product Name Z-A</option>
              <option value="3" {{$order_by == 3 ? 'selected' : ''}}>Price Low-High</option>
              <option value="4" {{$order_by == 4 ? 'selected' : ''}}>Price High-Low</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-outline-secondary">Filter</button>
        <a href="{{route('products.create')}}" class="btn btn-primary" role="button">Create Product</a>
      </div>
    </div>
  </form>

  @include('products._product_list', ['products' => $products])

  {!! $products->appends(Request::except('page'))->render() !!}
@endsection
