@extends('layouts.app')
@section('title', 'Customers')

@section('content')
    <div class="m-1 p-1">
      <h3 class="p-3 bg-primary text-white text-center">Customers</h3>
      <form action="{{route('customers.index')}}" method="get">
          <div class="form-group top-inline">
              <label for="cust_name">Search Customer</label>
              <input type="text" id="cust_name" placeholder="Search Customer" name="cust_name"
                  class="form-control-input" value="{{!is_null($keyword) ? $keyword : '' }}" />
              <a href="{{route('customers.create')}}" class="btn btn-primary btn-add">Create Customer</a>
          </div>
          <div class="form-group">
              <label for="order_by">Order By</label>
              <select id="order_by" name="order_by" class="form-control">
                  <option value="0" {{$order == 0 ? 'selected' : ''}}>Billing Name A-Z</option>
                  <option value="1" {{$order == 1 ? 'selected' : ''}}>Billing Name Z-A</option>
                  <option value="2" {{$order == 2 ? 'selected' : ''}}>Shipping Name A-Z</option>
                  <option value="3" {{$order == 3 ? 'selected' : ''}}>Shipping Name Z-A</option>
                  <option value="4" {{$order == 4 ? 'selected' : ''}}>Telephone ASC</option>
                  <option value="5" {{$order == 5 ? 'selected' : ''}}>Telephone DESC</option>
              </select>
          </div>
          <div class="form-group">
              <label for="page_size">Page Size</label>
              <select id="page_size" name="page_size" class="form-control">
                  <option value="10" {{ $pagesize == 10 ? 'selected' : '' }}>10</option>
                  <option value="25" {{ $pagesize == 25 ? 'selected' : '' }}>25</option>
                  <option value="50" {{ $pagesize == 50 ? 'selected' : '' }}>50</option>
              </select>
          </div>
          <button type="submit" class="btn btn-outline-secondary">Filter</button>
      </form>
      <table class="table table-striped table-bordered">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Bill To</th>
                  <th>Bill To Address</th>
                  <th>Bill To Prov</th>
                  <th>Bill To City</th>
                  <th>Bill To Postal</th>
                  <th>Ship To</th>
                  <th>Ship To Address</th>
                  <th>Ship To Prov</th>
                  <th>Ship To City</th>
                  <th>Ship To Postal</th>
                  <th>Telephone</th>
                  <th>Contact</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
            @if(count($customers) == 0)
              <tr><td colspan="14" class="text-center">No Customers found</td></tr>
            @else
              @foreach ($customers as $customer)
                <tr>
                  <td>{{$customer->id}}</td>
                  <td>{{$customer->bill_name}}</td>
                  <td>{{$customer->bill_addr}}</td>
                  <td>{{$customer->bill_prov}}</td>
                  <td>{{$customer->bill_city}}</td>
                  <td>{{$customer->bill_postal}}</td>
                  <td>{{$customer->ship_name}}</td>
                  <td>{{$customer->ship_addr}}</td>
                  <td>{{$customer->ship_prov}}</td>
                  <td>{{$customer->ship_city}}</td>
                  <td>{{$customer->ship_postal}}</td>
                  <td>{{$customer->contact1_tel}}</td>
                  <td>{{$customer->contact1_firstname . ' ' . $customer->contact1_lastname}}</td>
                  <td>
                    <a href="{{route('customers.edit', $customer->id)}}" class="btn btn-primary btn-edit">Edit</a>
                  </td>
                </tr>
              @endforeach
            @endif

          </tbody>
      </table>
      <div class="row text-center">
        {!! $customers->appends(Request::except('page'))->render() !!}
      </div>
    </div>
@endsection
