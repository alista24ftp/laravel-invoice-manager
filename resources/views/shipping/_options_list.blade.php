@if (count($options))
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Option Name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($options as $opt)
        <tr>
          <td>{{$opt->id}}</td>
          <td>{{$opt->option}}</td>
          <td>
            <a href="{{route('shipping.edit', $opt->id)}}" class="btn btn-sm btn-info" role="button">
              <i class="far fa-edit"></i>
            </a>
            <form action="{{route('shipping.destroy', $opt->id)}}" method="POST" class="d-inline-block"
              onsubmit="return confirm('Are you sure you want to delete this option?');">
              {{csrf_field()}}
              {{method_field('DELETE')}}
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="far fa-trash-alt"></i>
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <div class="bg-secondary text-white text-center p-3">
    No Shipping Options Found
  </div>
@endif
