@if (count($taxes))
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Province</th>
        <th>Description</th>
        <th>Rate</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($taxes as $tax)
        <tr>
          <td>{{$tax->id}}</td>
          <td>{{$tax->province}}</td>
          <td>{{$tax->description}}</td>
          <td>{{$tax->rate}}%</td>
          <td>
            <a href="{{route('taxes.edit', $tax->id)}}" class="btn btn-sm btn-info" role="button">
              <i class="far fa-edit"></i>
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <div class="bg-secondary text-white text-center p-3">
    No Tax Info Found
  </div>
@endif
