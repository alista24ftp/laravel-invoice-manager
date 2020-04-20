@extends('layouts.app')
@section('title', 'User Profile')

@section('content')
  <div class="col-9">
    <h1>User Profile - {{$user->username}}</h1>
    <p>First Name: {{$user->firstname}}</p>
    <p>Last Name: {{$user->lastname}}</p>
  </div>
@endsection
