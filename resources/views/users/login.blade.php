@extends('layouts.app')
@section('title', 'Log In')

@section('content')
  <div class="row justify-content-center mt-5">
    <div class="col-4">
      <div class="card">
        <div class="card-header">
          Login
        </div>
        <div class="card-body">
          <form id="login-form" action="{{route('login')}}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                  class="form-control" value="{{old('username')}}" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                  class="form-control" value="{{old('password')}}" />
            </div>
            <div class="form-group row">
              <div class="col text-center">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
