<div class="card">
  <h5 class="card-header">Invoice Info</h5>
  <div class="card-body">
    <div class="form-row">
      <div class="form-group col-4">
        <label for="invoice_no">Invoice #</label>
        <input type="text" id="invoice_no" class="form-control" name="invoice_no"
          value="{{old('invoice_no', $invoice->invoice_no)}}" readonly />
        <input type="hidden" id="old_invoice_no" class="form-control" name="old_invoice_no"
          value="{{old('old_invoice_no', $invoice->invoice_no)}}" />
      </div>
      <div class="form-group col-4">
        <p>Payment Status: <span id="pay_status_display">{{$invoice->textPayStatus()}}</span></p>
        <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#paymentModal">
          Edit Payment
        </button>
        @include('invoices._edit_payment', ['invoice' => $invoice])
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-4">
        <label for="company_tax_reg">Tax Reg. No</label>
        <input type="text" id="company_tax_reg" name="company_tax_reg" class="form-control"
          value="{{old('company_tax_reg', ($invoice->company_tax_reg ?? $company->tax_reg))}}" />
      </div>
      <div class="form-group col-4">
        <label for="create_date">Date</label>
        <input type="date" name="create_date" id="create_date" class="form-control"
          value="{{old('create_date', ($invoice->create_date ?? Carbon\Carbon::now()->format('Y-m-d')))}}" />
      </div>
      <div class="form-group col-4">
        <label for="po_no">PO No.</label>
        <input type="text" id="po_no" name="po_no" class="form-control" value="{{old('po_no', $invoice->po_no)}}" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-4">
        <label for="sales_rep">Sales Rep</label>
        <select id="sales_rep_select" name="sales_rep_select" class="form-control">
          <option value="" selected>Choose Sales Rep</option>
          @foreach($sales_reps as $rep)
            <option value="{{$rep->firstname . ' ' . $rep->lastname}}"
              {{old('sales_rep', $invoice->sales_rep) == ($rep->firstname . ' ' . $rep->lastname) ? 'selected' : ''}}>
              {{$rep->firstname . ' ' . $rep->lastname}}
            </option>
          @endforeach
        </select>
        <p id="sales_rep_display">
          {{old('sales_rep', ($invoice->sales_rep ? $invoice->sales_rep : '(None)'))}}
        </p>
        <input type="hidden" class="form-control" id="sales_rep" name="sales_rep"
          value="{{old('sales_rep', $invoice->sales_rep)}}" />
      </div>
      <div class="form-group col-4">
        <label for="terms">Terms</label>
        <select id="terms_select" name="terms_select" class="form-control">
          <option value="" selected disabled>Select Terms</option>
          @foreach ($payment_terms as $terms)
            <option value="{{$terms->option}}" data-period="{{$terms->period}}"
              {{old('terms', $invoice->terms) == $terms->option ? 'selected' : ''}}>
              {{$terms->option}} {{$terms->period ? "($terms->period DAY PERIOD)" : ''}}
            </option>
          @endforeach
        </select>
        <p id="terms_display">
          {{old('terms', ($invoice->terms ? strtoupper($invoice->terms) : 'N/A'))}}
          {{old('terms_period', ($invoice->terms_period ? "($invoice->terms_period DAY PERIOD)" : ''))}}
        </p>
        <input type="hidden" id="terms" name="terms" class="form-control" value="{{old('terms', $invoice->terms)}}">
        <input type="hidden" id="terms_period" name="terms_period" class="form-control"
          value="{{old('terms_period', $invoice->terms_period)}}" />
      </div>
      <div class="form-group col-4">
        <label for="via">VIA</label>
        <select id="via_select" name="via_select" class="form-control">
          <option value="" selected disabled>Select Shipping Options</option>
          @foreach ($shipping_options as $option)
            <option value="{{$option->option}}" {{old('via', $invoice->via) == $option->option ? 'selected' : ''}}>
              {{$option->option}}
            </option>
          @endforeach
        </select>
        <p id="via_display">
          {{old('via', ($invoice->via ? $invoice->via : 'N/A'))}}
        </p>
        <input type="hidden" id="via" name="via" class="form-control" value="{{old('via', $invoice->via)}}" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-6">
        <label for="memo">Memo</label>
        <textarea name="memo" id="memo" class="form-control">
          {{old('memo', $invoice->memo)}}
        </textarea>
      </div>
      <div class="form-group col-6">
        <label for="notes">Notes</label>
        <textarea name="notes" id="notes" class="form-control">
          {{old('notes', $invoice->notes)}}
        </textarea>
      </div>
    </div>
  </div>
</div>
