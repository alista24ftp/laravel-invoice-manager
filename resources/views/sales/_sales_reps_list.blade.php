@if(count($reps))
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Tel</th>
        <th>Cell</th>
        <th>Email</th>
        <th>Status</th>
        <th colspan="2">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($reps as $rep)
        <tr>
          <td>{{$rep->id}}</td>
          <td>{{$rep->firstname}}</td>
          <td>{{$rep->lastname}}</td>
          <td>{{$rep->tel}}</td>
          <td>{{$rep->cell}}</td>
          <td>{{$rep->email}}</td>
          <td>{{$rep->trashed() ? 'Inactive' : 'Active'}}</td>
          <td colspan="2">
            <a href="{{route('sales.edit', $rep->id)}}" class="btn btn-sm btn-info">
              Edit
            </a>
            @if($rep->trashed())
              <a href="#">
                <form action="{{route('sales.restore', $rep->id)}}" method="POST">
                  {{csrf_field()}}
                  {{method_field('PUT')}}
                  <button type="submit" class="btn btn-sm btn-success">
                    Restore
                  </button>
                </form>
              </a>
            @else
              <a href="#">
                <form action="{{route('sales.destroy', $rep->id)}}" method="POST">
                  {{csrf_field()}}
                  {{method_field('DELETE')}}
                  <button type="submit" class="btn btn-sm btn-danger">
                    Delete
                  </button>
                </form>
              </a>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <div class="empty-block">
    No Sales Reps Found
  </div>
@endif
