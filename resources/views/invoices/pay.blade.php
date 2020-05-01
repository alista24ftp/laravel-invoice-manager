@extends('layouts.app')
@section('title', 'Invoice Pay')

@section('content')
  <h3 class="bg-primary p-1 m-1">Pay Invoice {{$invoice->invoice_no}}</h3>
  <form id="payment_form" method="POST" action="{{route('invoices.pay_modify', $invoice->invoice_no)}}"
    enctype="multipart/form-data">
    {{ csrf_field() }}

    <input type="hidden" name="invoice_no" id="invoice_no" value="{{$invoice->invoice_no}}" />
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="paid">Payment Status</label>
          <div class="custom-control custom-switch">
            <input class="custom-control-input" id="paid" name="paid" type="checkbox" value="{{$invoice->paid}}"
              {{$invoice->paid ? 'checked' : ''}}>
            <label class="custom-control-label {{$invoice->paid ? 'paid' : 'unpaid'}}" id="pay_status" for="paid">
              {{$invoice->textPayStatus()}}
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="proofs">Proof of Payment</label>
          <input type="file" name="proofs[]" id="proofs" class="form-control-file" multiple />
        </div>
      </div>
    </div>
    <div class="row">
      <div id="preview">
        <ol id="uploaded_files"></ol>
      </div>
    </div>
    <div class="form-row justify-content-center">
      <a class="btn btn-outline-secondary m-3" href="{{url()->previous()}}">Cancel</a>
      <button type="submit" class="btn btn-primary m-3">Save Payment</button>
    </div>
  </form>
@endsection

@section('customjs')
  <script>
    $(document).ready(function() {
      var allProofs = [];

      // toggle payment status
      $(document).on('change', '#paid', function(e){
        let oldPayStatus = $(this).val();
        let newPayStatus = oldPayStatus == 1 ? 0 : 1;
        $(this).val(newPayStatus);
        if(newPayStatus){
          $('#pay_status').text('PAID');
          $('#pay_status').removeClass('unpaid');
          $('#pay_status').addClass('paid');
        }else{
          $('#pay_status').text('UNPAID');
          $('#pay_status').removeClass('paid');
          $('#pay_status').addClass('unpaid');
        }
      });

      $(document).on('change', '#proofs', function(e){
        let newFilesToUpload = $(this)[0].files;
        for(let i=0; i<newFilesToUpload.length; i++){
          allProofs.push(newFilesToUpload[i]);
          var fileItem = document.createElement('li');
          var img = new Image(100, 200);
          img.src = newFilesToUpload[i].name;
          fileItem.appendChild(img);
          document.querySelector('#uploaded_files').appendChild(fileItem);
        }
        console.log(allProofs);
      });
    });
  </script>
@endsection
