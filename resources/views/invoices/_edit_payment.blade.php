<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Edit Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label for="paid">Payment Status</label>
            <div class="custom-control custom-switch">
              <input class="custom-control-input" id="paid" name="paid" type="checkbox" value="{{$invoice->paid ? $invoice->paid : 0}}"
                {{$invoice->paid ? 'checked' : ''}}>
              <label class="custom-control-label {{$invoice->paid ? 'paid' : 'unpaid'}}" id="pay_status" for="paid">
                {{$invoice->textPayStatus()}}
              </label>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="upload_proof">Upload Payment Proof</label>
            <input type="file" name="upload_proof" id="upload_proof" class="form-control-file" />
          </div>
        </div>

        <div id="preview" class="row d-flex flex-row flex-wrap">
          @foreach ($invoice->paymentProofs as $proof_idx => $proof)
            <div id="preview_item_{{$proof->id ? $proof->id : $proof_idx}}" class="preview_item">
              <img src="{{$proof->path}}" alt="Image not found"
                id="proof_img_{{$proof->id ? $proof->id : $proof_idx}}" class="proof_img" width="150" height="150">
              <a href="#" id="remove_proof_{{$proof->id ? $proof->id : $proof_idx}}" class="remove_proof">Remove</a>
            </div>
          @endforeach
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Complete</button>
      </div>
    </div>
  </div>
</div>
