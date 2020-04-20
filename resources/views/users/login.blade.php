@extends('layouts.app')
@section('title', 'Log In')

@section('content')
  <div class="col-4 offset-4">
    <h3 class="p-3 bg-primary text-white text-center">Login</h3>
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
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
@endsection
