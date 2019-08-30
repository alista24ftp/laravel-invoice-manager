<form action="{{route('logout')}}" method="POST">
  {{csrf_field()}}
  {{method_field('DELETE')}}
  <button type="submit" class="btn btn-block btn-danger" name="button">Logout</button>
</form>
