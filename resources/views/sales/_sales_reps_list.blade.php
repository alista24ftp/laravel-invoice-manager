@if(count($reps))
  <ul class="list-unstyled border mt-1">
    @foreach($reps as $rep)
      <li class="media p-3">
        <div class="media-body">
          <div class="media-heading mt-0 mb-1 d-flex justify-content-between">
            <p class="mt-0 mb-1">{{$rep->firstname . ' ' . $rep->lastname}}</p>
            <p class="mt-0 mb-1">Status: {{$rep->trashed() ? 'Inactive' : 'Active'}}</p>
          </div>
          <div class="media-body mt-0">
            <p class="mt-0 mb-1">Tel: {{$rep->tel}} {{$rep->cell ? "Cell: $rep->cell" : ""}}</p>
            @if($rep->email)
              <p class="mt-0 mb-1">Email: {{$rep->email}}</p>
            @endif
          </div>
          <small class="media-body meta text-secondary">
            @if($rep->trashed())
              <form action="{{route('sales.restore', $rep->id)}}" method="POST" class="d-inline-block"
                onsubmit="return confirm('Are you sure you want to restore this sales rep?');">
                {{csrf_field()}}
                {{method_field('PUT')}}
                <button type="submit" class="btn btn-sm btn-success">
                  <i class="fas fa-trash-restore-alt"></i>
                  Restore
                </button>
              </form>
            @else
              <a href="{{route('sales.edit', $rep->id)}}" class="btn btn-sm btn-info" role="button">
                <i class="far fa-edit"></i>
                Edit
              </a>
              <form action="{{route('sales.destroy', $rep->id)}}" method="POST" class="d-inline-block"
                onsubmit="return confirm('Are you sure you want to delete this sales rep?');">
                {{csrf_field()}}
                {{method_field('DELETE')}}
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="far fa-trash-alt"></i>
                  Delete
                </button>
              </form>
            @endif
          </small>
        </div>
      </li>
      @if(!$loop->last)
        <hr class="my-0">
      @endif
    @endforeach
  </ul>
@else
  <div class="bg-secondary p-3 text-center text-white">
    No Sales Reps Found
  </div>
@endif
