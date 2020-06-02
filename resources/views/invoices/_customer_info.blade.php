<div class="card">
  <div class="card-header d-flex align-items-center">
    <h5 class="mr-3 mb-0">Customer Info</h5>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#customerModal">
      Change Customer
    </button>
  </div>

  @include('invoices._customer_change')

  <div class="card-body">
    <input type="hidden" name="customer_id" id="customer_id" value="{{$invoice->customer_id}}" />
    <!-- Billing Info -->
    <div class="border m-1 p-1">
      <div class="form-row">
        <div class="form-group col-6">
          <label for="bill_name">Billing Name</label>
          <input type="text" name="bill_name" id="bill_name" class="form-control"
            value="{{old('bill_name', $invoice->bill_name)}}" />
        </div>
        <div class="form-group col-6">
          <label for="bill_addr">Billing Address</label>
          <input type="text" name="bill_addr" id="bill_addr" class="form-control"
            value="{{old('bill_addr', $invoice->bill_addr)}}" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-3">
          <label for="bill_prov">Province</label>
          <select id="bill_prov" name="bill_prov" class="form-control">
            <option value="" selected disabled>Select a province</option>
            @foreach($provinces as $abbr => $prov)
              <option value="{{$abbr}}" {{old('bill_prov', $invoice->bill_prov) == $abbr ? 'selected' : ''}}>
                {{$abbr}}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-6">
          <label for="bill_city">City</label>
          <input type="text" name="bill_city" id="bill_city" class="form-control"
            value="{{old('bill_city', $invoice->bill_city)}}" />
        </div>
        <div class="form-group col-3">
          <label for="bill_postal">Postal Code</label>
          <input type="text" name="bill_postal" id="bill_postal"
            class="form-control" maxlength="6" value="{{old('bill_postal', $invoice->bill_postal)}}" />
        </div>
      </div>
    </div>

    <!-- Shipping Info -->
    <div class="border m-1 p-1">
      <div class="form-row">
        <div class="form-group col-6">
          <label for="ship_name">Shipping Name</label>
          <input type="text" name="ship_name" id="ship_name" class="form-control"
            value="{{old('ship_name', $invoice->ship_name)}}" />
        </div>
        <div class="form-group col-6">
          <label for="ship_addr">Shipping Address</label>
          <input type="text" name="ship_addr" id="ship_addr" class="form-control"
            value="{{old('ship_addr', $invoice->ship_addr)}}" />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-3">
          <label for="ship_prov">Province</label>
          <select id="ship_prov" name="ship_prov" class="form-control">
            <option value="" data-taxrate="0" data-taxdesc="No Tax" selected disabled>
              Select a province
            </option>
            @foreach($provinces as $abbr => $prov)
              <option value="{{$abbr}}"
                data-taxrate="{{$taxes->where('province', $abbr)->first()->rate}}"
                data-taxdesc="{{$taxes->where('province', $abbr)->first()->description}}"
                {{old('ship_prov', $invoice->ship_prov) == $abbr ? 'selected' : ''}}>
                {{$abbr}}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-6">
          <label for="ship_city">City</label>
          <input type="text" name="ship_city" id="ship_city" class="form-control"
            value="{{old('ship_city', $invoice->ship_city)}}" />
        </div>
        <div class="form-group col-3">
          <label for="ship_postal">Postal Code</label>
          <input type="text" name="ship_postal" id="ship_postal"
            class="form-control" maxlength="6" value="{{old('ship_postal', $invoice->ship_postal)}}" />
        </div>
      </div>
    </div>

    <!-- Other Customer Info -->
    <div class="form-row">
      <div class="form-group col-3">
        <label for="customer_tel">Telephone</label>
        <input type="text" name="customer_tel" id="customer_tel"
          class="form-control" value="{{old('customer_tel', $invoice->customer_tel)}}" maxlength="11" />
      </div>
      <div class="form-group col-3">
        <label for="customer_fax">Fax</label>
        <input type="text" name="customer_fax" id="customer_fax"
          class="form-control" value="{{old('customer_fax', $invoice->customer_fax)}}" maxlength="11" />
      </div>
      <div class="form-group col-3">
        <label for="customer_contact1">Contact 1</label>
        <input type="text" name="customer_contact1" id="customer_contact1" class="form-control"
          value="{{old('customer_contact1', $invoice->customer_contact1)}}" />
      </div>
      <div class="form-group col-3">
        <label for="customer_contact2">Contact 2</label>
        <input type="text" name="customer_contact2" id="customer_contact2" class="form-control"
          value="{{old('customer_contact2', $invoice->customer_contact2)}}" />
      </div>
    </div>
  </div>
</div>
