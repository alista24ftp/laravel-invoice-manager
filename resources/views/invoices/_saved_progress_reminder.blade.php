@if(Cache::has('user_' . Auth::user()->id . '_invoice'))
  <div id="progress_alert" class="alert alert-info alert-dismissible fade show" role="alert">
    <p class="mt-2 mb-2">There is a previous saved invoice progress.</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <p class="mt-2 mb-2">Would you like to
      <a href="{{route('invoices.restore')}}" id="restore_progress" class="alert-link">restore it</a> or
      <a href="#" id="delete_progress" class="alert-link">delete it</a>?
    </p>
  </div>
@endif
