@extends('layouts.app')
@section('title', 'Payment Term Info')

@section('content')
  @include('shared._error')

  @if ($term->id)
    <form action="{{route('terms.update', $term->id)}}" method="POST">
      {{csrf_field()}}
      {{method_field('PUT')}}
  @else
    <form action="{{route('terms.store')}}" method="POST">
      {{csrf_field()}}
  @endif
    <div class="card">
      <h5 class="card-header">Payment Term Info</h5>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-3">
            <label for="option">Payment Term Name</label>
            <input type="text" id="option" name="option" class="form-control" value="{{old('option', $term->option)}}" />
          </div>
          <div class="form-group col-3">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="toggle_period" {{$term->period ? 'checked' : ''}} />
              <label for="toggle_period" class="custom-control-label">Set Payment Period</label>
            </div>
          </div>
          <div id="period_input" class="form-group col-3">
            <label for="period">Payment Period (Days)</label>
            @if($term->period)
              <input type="number" id="period" name="period" class="form-control"
                min="1" step="1" value="{{old('period', $term->period)}}" />
            @else
              <input type="hidden" id="period" name="period" value="" />
              <p id="no_period_msg">No Payment Term Period</p>
            @endif
          </div>

        </div>
      </div>
      <div class="card-footer row mx-0">
        <div class="col-6 text-right">
          <a href="{{route('terms.index')}}" class="btn btn-outline-secondary" role="button">
            Cancel
          </a>
        </div>
        <div class="col-6 text-left">
          <button type="submit" class="btn btn-primary">
            Save
          </button>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('customjs')
  <script>
    // Page initialization
    // document.addEventListener('DOMContentLoaded', function(event){});
    (function(){
      let termPeriodEl = document.getElementById('period_input'); // <div class="form-group" id="period_input"></div>
      let periodInputEl = document.getElementById('period'); // <input id="period" />
      let oldPeriodVal = periodInputEl.value;
      let togglePeriodEl = document.getElementById('toggle_period'); // <input type="checkbox" id="toggle_period" />

      // When set period checkbox is toggled
      togglePeriodEl.addEventListener('change', function(event){
        if(togglePeriodEl.checked){
          // remove "no period msg"
          let noPeriodMsg = document.getElementById('no_period_msg');
          noPeriodMsg.parentNode.removeChild(noPeriodMsg);
          // modify period input element
          periodInputEl.type = 'number';
          periodInputEl.min = 1;
          periodInputEl.step = 1;
          periodInputEl.classList.add('form-control');
          periodInputEl.value = oldPeriodVal;
        }else{
          // add "no period msg"
          let noPeriodMsg = document.createElement('p');
          noPeriodMsg.id = 'no_period_msg';
          noPeriodMsg.textContent = 'No Payment Term Period';
          termPeriodEl.appendChild(noPeriodMsg);
          // modify period input element
          periodInputEl.type = "hidden";
          periodInputEl.value = '';
        }
      });

      periodInputEl.addEventListener('change', function(event){
        oldPeriodVal = event.target.value == '' ? oldPeriodVal : event.target.value;
      });
    })();
  </script>
@endsection
