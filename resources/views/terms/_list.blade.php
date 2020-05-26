@if(count($terms))
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Option</th>
        <th>Period (Days)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($terms as $term)
        <tr>
          <td>{{$term->id}}</td>
          <td>{{$term->option}}</td>
          <td>{{$term->period ? $term->period : 'N/A'}}</td>
          <td>
            <a href="{{route('terms.edit', $term->id)}}" class="btn btn-sm btn-info" role="button">
              <i class="far fa-edit"></i>
            </a>
            <form action="{{route('terms.destroy', $term->id)}}" method="POST" class="d-inline-block"
              onsubmit="return confirm('Are you sure you want to delete this payment term?');">
              {{csrf_field()}}
              {{method_field('DELETE')}}
              <button class="btn btn-sm btn-danger" type="submit">
                <i class="far fa-trash-alt"></i>
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <div class="bg-secondary p-3 text-white text-center">
    No Payment Terms Found
  </div>
@endif
