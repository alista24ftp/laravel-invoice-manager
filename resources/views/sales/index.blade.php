@extends('layouts.app')
@section('title', 'Sales Reps')

@section('content')
  <h1 class="h1 text-center text-white bg-primary mt-1">Sales Reps</h1>
  <form action="{{route('sales.index')}}" method="GET">
    <div class="card">
      <h5 class="card-header">Filter Sales Reps</h5>
      <div class="card-body border">
        <div class="form-row">
          <div class="form-group col-3">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
              <option value="0" {{$status == 0 ? 'selected' : ''}}>All</option>
              <option value="1" {{$status == 1 ? 'selected' : ''}}>Active</option>
              <option value="2" {{$status == 2 ? 'selected' : ''}}>Inactive</option>
            </select>
          </div>
          <div class="form-group col-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{$name}}" />
          </div>
          <div class="form-group col-3">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{$phone}}" />
          </div>
          <div class="form-group col-3">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="{{$email}}" />
          </div>
        </div>
      </div>
      <div class="card-body border">
        <div class="form-group col">
          <label for="order_by">Order By</label>
          <select id="order_by" name="order_by" class="form-control">
            <option value="0" {{$order_by == 0 ? 'selected' : ''}}>First Name A-Z</option>
            <option value="1" {{$order_by == 1 ? 'selected' : ''}}>First Name Z-A</option>
            <option value="2" {{$order_by == 2 ? 'selected' : ''}}>Last Name A-Z</option>
            <option value="3" {{$order_by == 3 ? 'selected' : ''}}>Last Name Z-A</option>
          </select>
        </div>
      </div>
      <div class="card-body border">
        <button type="submit" class="btn btn-secondary">Filter</button>
        <a href="{{route('sales.create')}}" class="btn btn-primary">Add Sales Rep</a>
      </div>
    </div>
  </form>

  @include('sales._sales_reps_list', ['reps' => $reps])

  <div class="row text-center">
    {!! $reps->appends(Request::except('page'))->render() !!}
  </div>
@endsection
