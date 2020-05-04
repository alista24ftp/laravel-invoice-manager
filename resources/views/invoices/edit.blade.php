@extends('layouts.app')
@section('title', 'Invoice Edit')

@section('content')
  <h3 class="bg-primary p-1 m-1">Edit Invoice {{$invoice->invoice_no}}</h3>
  <form id="invoice_form" action="{{route('invoices.update', $invoice->invoice_no)}}" method="POST">
    @include('shared._error')
    {{csrf_field()}}
    {{method_field('PUT')}}
    <input type="hidden" name="op" value="edit" />

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

    <!-- INVOICE INFO -->
    <div class="card">
      <h5 class="card-header">Invoice Info</h5>
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="invoice_no">Invoice #</label>
              <input type="text" id="invoice_no" class="form-control" name="invoice_no" value="{{$invoice->invoice_no}}" readonly>
              <input type="hidden" id="old_invoice_no" class="form-control" name="old_invoice_no" value="{{$invoice->invoice_no}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="paid">Payment Status</label>
              <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#paymentModal">
                Edit Payment
              </button>
              @include('invoices._edit_payment', ['invoice' => $invoice])
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="company_tax_reg">Tax Reg. No</label>
              <input type="text" id="company_tax_reg" name="company_tax_reg" class="form-control" value="{{$invoice->company_tax_reg}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="create_date">Date</label>
              <input type="date" name="create_date" id="create_date" class="form-control" value="{{$invoice->create_date}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="po_no">PO No.</label>
              <input type="text" id="po_no" name="po_no" class="form-control" value="{{$invoice->po_no}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="sales_rep">Sales Rep</label>
              <select id="sales_rep_select" name="sales_rep_select" class="form-control">
                <option value="" {{is_null($invoice->sales_rep) || $invoice->sales_rep == '' ? 'selected' : ''}}>Choose Sales Rep</option>
                @foreach($sales_reps as $rep)
                  <option value="{{$rep->firstname . ' ' . $rep->lastname}}" {{$invoice->sales_rep == ($rep->firstname . ' ' . $rep->lastname) ? 'selected' : ''}}>
                    {{$rep->firstname . ' ' . $rep->lastname}}
                  </option>
                @endforeach
              </select>
              <input type="text" class="form-control" id="sales_rep" name="sales_rep" value="{{$invoice->sales_rep}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="terms">Terms</label>
              <select id="terms_select" name="terms_select" class="form-control">
                <option value="" readonly>Select Terms</option>
                @foreach ($payment_terms as $terms)
                  <option value="{{$terms->option}}" {{$invoice->terms == $terms->option ? 'selected' : ''}}>
                    {{$terms->option}}
                  </option>
                @endforeach
              </select>
              <input type="text" id="terms" name="terms" class="form-control" value="{{$invoice->terms}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="via">VIA</label>
              <select id="via_select" name="via_select" class="form-control">
                <option value="" readonly>Select Shipping Options</option>
                @foreach ($shipping_options as $option)
                  <option value="{{$option->option}}" {{$invoice->via == $option->option ? 'selected' : ''}}>
                    {{$option->option}}
                  </option>
                @endforeach
              </select>
              <input type="text" id="via" name="via" class="form-control" value="{{$invoice->via}}">
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="memo">Memo</label>
              <textarea name="memo" id="memo" class="form-control">
                {{$invoice->memo}}
              </textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="notes">Notes</label>
              <textarea name="notes" id="notes" class="form-control">
                {{$invoice->notes}}
              </textarea>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- COMPANY INFO -->
    <div class="card">
      <h5 class="card-header">Company Info</h5>
      <div class="card-body">
        <input type="hidden" id="company_id" name="company_id" value="{{$invoice->company_id}}">
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="company_name">Company Name</label>
              <input type="text" name="company_name" id="company_name" class="form-control" value="{{$invoice->company_name}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-9">
            <div class="form-group">
              <label for="company_mail_addr">Mailing Address</label>
              <input type="text" id="company_mail_addr" name="company_mail_addr" class="form-control" value="{{$invoice->company_mail_addr}}">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="company_mail_postal">Postal Code</label>
              <input type="text" name="company_mail_postal" id="company_mail_postal"
                class="form-control" maxlength="6" value="{{$invoice->company_mail_postal}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-9">
            <div class="form-group">
              <label for="company_ware_addr">Warehouse Address</label>
              <input type="text" id="company_ware_addr" name="company_ware_addr" class="form-control" value="{{$invoice->company_ware_addr}}">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="company_ware_postal">Postal Code</label>
              <input type="text" name="company_ware_postal" id="company_ware_postal"
                class="form-control" maxlength="6" value="{{$invoice->company_ware_postal}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="company_email">Company Email</label>
              <input type="text" class="form-control" id="company_email" name="company_email" value="{{$invoice->company_email}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="company_website">Company Website</label>
              <input type="text" class="form-control" id="company_website" name="company_website" value="{{$invoice->company_website}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="company_tel">Company Tel</label>
              <input type="text" name="company_tel" id="company_tel"
                class="form-control" maxlength="11" value="{{$invoice->company_tel}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="company_fax">Company Fax</label>
              <input type="text" name="company_fax" id="company_fax"
                class="form-control" maxlength="11" value="{{$invoice->company_fax}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="company_tollfree">Company Toll-Free</label>
              <input type="text" name="company_tollfree" id="company_tollfree"
                class="form-control" maxlength="11" value="{{$invoice->company_tollfree}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              <label for="company_contact_fname">Contact First Name</label>
              <input type="text" id="company_contact_fname" name="company_contact_fname"
                class="form-control" maxlength="30" value="{{$invoice->company_contact_fname}}">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="company_contact_lname">Contact Last Name</label>
              <input type="text" id="company_contact_lname" name="company_contact_lname"
                class="form-control" maxlength="30" value="{{$invoice->company_contact_lname}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="company_contact_email">Contact Email</label>
              <input type="text" id="company_contact_email" name="company_contact_email"
                class="form-control" maxlength="30" value="{{$invoice->company_contact_email}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="company_contact_tel">Contact Tel</label>
              <input type="text" id="company_contact_tel" name="company_contact_tel"
                class="form-control" maxlength="11" value="{{$invoice->company_contact_tel}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="company_contact_cell">Contact Cell</label>
              <input type="text" id="company_contact_cell" name="company_contact_cell"
                class="form-control" maxlength="11" value="{{$invoice->company_contact_cell}}">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="card">
      <div class="card-header">
        <h5>Customer Info</h5>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#customerModal">
          Change Customer
        </button>
      </div>
      <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="customerModalLabel">Change Customer</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <div class="form-group">
                <label for="customer_key" class="col-form-label">Search Customer</label>
                <input type="text" name="customer_key" id="customer_key" class="form-control"
                  placeholder="Bill Name, Ship Name, Telephone, Postal Code, Contact">
                <span>(Min 3 characters)</span>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <input type="hidden" name="customer_id" id="customer_id" value="{{$invoice->customer_id}}">
        <div class="border m-1 p-1">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="bill_name">Billing Name</label>
                <input type="text" name="bill_name" id="bill_name" class="form-control" value="{{$invoice->bill_name}}">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="bill_addr">Billing Address</label>
                <input type="text" name="bill_addr" id="bill_addr" class="form-control" value="{{$invoice->bill_addr}}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="bill_prov">Province</label>
                <select id="bill_prov" name="bill_prov" class="form-control">
                  <option value="" disabled>Select a province</option>
                  @foreach($provinces as $abbr => $prov)
                    <option value="{{$abbr}}" {{$invoice->bill_prov == $abbr ? 'selected' : ''}}>
                      {{$abbr}} - {{$prov}}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="bill_city">City</label>
                <input type="text" name="bill_city" id="bill_city" class="form-control" value="{{$invoice->bill_city}}">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="bill_postal">Postal Code</label>
                <input type="text" name="bill_postal" id="bill_postal"
                  class="form-control" maxlength="6" value="{{$invoice->bill_postal}}">
              </div>
            </div>
          </div>
        </div>

        <!-- Shipping Info -->
        <div class="border m-1 p-1">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="ship_name">Shipping Name</label>
                <input type="text" name="ship_name" id="ship_name" class="form-control" value="{{$invoice->ship_name}}">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="ship_addr">Shipping Address</label>
                <input type="text" name="ship_addr" id="ship_addr" class="form-control" value="{{$invoice->ship_addr}}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="ship_prov">Province</label>
                <select id="ship_prov" name="ship_prov" class="form-control">
                  <option value="" data-taxrate="{{$taxes->where('province', '')->first()->rate}}"
                    data-taxdesc="{{$taxes->where('province', '')->first()->description}}"
                    disabled>
                    Select a province
                  </option>
                  @foreach($provinces as $abbr => $prov)
                    <option value="{{$abbr}}"
                      data-taxrate="{{$taxes->where('province', $abbr)->first()->rate}}"
                      data-taxdesc="{{$taxes->where('province', $abbr)->first()->description}}"
                      {{$invoice->ship_prov == $abbr ? 'selected' : ''}}>
                      {{$abbr}} - {{$prov}}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="ship_city">City</label>
                <input type="text" name="ship_city" id="ship_city" class="form-control" value="{{$invoice->ship_city}}">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="ship_postal">Postal Code</label>
                <input type="text" name="ship_postal" id="ship_postal"
                  class="form-control" maxlength="6" value="{{$invoice->ship_postal}}">
              </div>
            </div>
          </div>
        </div>

        <!-- Other Customer Info -->
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="customer_tel">Telephone</label>
              <input type="text" name="customer_tel" id="customer_tel"
                class="form-control" value="{{$invoice->customer_tel}}" maxlength="11">
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="customer_fax">Fax</label>
              <input type="text" name="customer_fax" id="customer_fax"
                class="form-control" value="{{$invoice->customer_fax}}" maxlength="11">
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="customer_contact1">Contact 1</label>
              <input type="text" name="customer_contact1" id="customer_contact1" class="form-control" value="{{$invoice->customer_contact1}}">
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="customer_contact2">Contact 2</label>
              <input type="text" name="customer_contact2" id="customer_contact2" class="form-control" value="{{$invoice->customer_contact2}}">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ORDER INFO -->
    <div class="card">
      <h5 class="card-header">Orders</h5>
      <div class="card-body">
        <button type="button" id="orders_add" class="btn btn-sm btn-outline-primary">Add Order</button>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Description</th>
              <th>Unit Price</th>
              <th>Quantity</th>
              <th>Discount</th>
              <th>Total</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="orders_list">
            @foreach($invoice->orders as $item_idx => $item)
              <tr class="order-item" id="orders_{{$item_idx}}">
                <td>
                  <select id="orders_{{$item_idx}}_product_select"
                    class="form-control product-select">
                    <option id="orders_{{$item_idx}}_products_-1" value="" data-price="0" selected>Choose a Product</option>
                    @foreach($products as $prod_idx => $product)
                      <option id="orders_{{$item_idx}}_products_{{$prod_idx}}" data-price="{{$product->price}}"
                        value="{{$product->name}}" {{$item->product == $product->name ? 'selected' : ''}}>
                        {{$product->name}}
                      </option>
                    @endforeach
                  </select>
                  <input type="text" name="orders[{{$item_idx}}][product]"
                    id="orders_{{$item_idx}}_product" class="form-control"
                    value="{{$item->product}}">
                </td>
                <td>
                  <input type="number" name="orders[{{$item_idx}}][price]"
                    id="orders_{{$item_idx}}_price" class="form-control product-price"
                    min="0" max="10000" step="0.01" value="{{$item->price}}">
                </td>
                <td>
                  <input type="number" name="orders[{{$item_idx}}][quantity]"
                    id="orders_{{$item_idx}}_quantity" class="form-control product-quantity"
                    value="{{$item->quantity}}">
                </td>
                <td>
                  <input type="number" name="orders[{{$item_idx}}][discount]"
                    id="orders_{{$item_idx}}_discount" class="form-control product-discount"
                    min="0" step="0.01"
                    value="{{$item->discount ? $item->discount : 0.00}}">
                </td>
                <td>
                  <input type="number" name="orders[{{$item_idx}}][total]"
                    id="orders_{{$item_idx}}_total" class="form-control product-total"
                    step="0.01"
                    value="{{$item->total}}">
                </td>
                <td>
                  <button type="button" id="orders_{{$item_idx}}_remove"
                    class="btn btn-sm btn-danger orders-remove">Remove</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="justify-content-end">
          <input type="hidden" name="tax_rate" value="{{$invoice->tax_rate}}" id="tax_rate">
          <input type="hidden" name="tax_description" value="{{$invoice->tax_description}}" id="tax_description">
          <div id="tax_info">
            <p>Tax: <span id="tax_info_rate">{{$invoice->tax_rate}}</span>% (<span id="tax_info_desc">{{$invoice->tax_description}}</span>)</p>
          </div>
          <div class="form-group row">
            <label class="col-2 col-form-label" for="freight">Freight</label>
            <div class="col-2">
              <input type="number" id="freight" name="freight" class="form-control"
                min="0" step="0.01" value="{{$invoice->freight}}">
            </div>
          </div>
          <hr>
          <div id="final_total">
            <p>Amount: $<span id="total_amount">{{$invoice->totalAmount()}}</span></p>
          </div>
        </div>
      </div>
    </div>
    <div class="form-row justify-content-center">
      <a class="btn btn-outline-secondary m-3" href="{{route('invoices.index')}}">Cancel</a>
      <button id="save_progress" type="button" class="btn btn-info m-3 save-progress">Save Progress</button>
      <button type="submit" class="btn btn-primary m-3">Save</button>
    </div>
  </form>


@endsection

@section('customstyles')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
@endsection

@section('customjs')
  <script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
  <script>
    // CUSTOM FUNCTIONS
    // Calculate total from orders
    function tallyTotals(){
      let totalAmount = 0;
      $('.product-total').each(function(index){
        let currTotal = $(this).val();
        if(!currTotal || isNaN(currTotal)){
          currTotal = 0;
          $(this).val(0);
        }else{
          currTotal = parseFloat(currTotal, 2);
        }
        totalAmount += currTotal;
      });
      return totalAmount;
    }

    // DOM rendered
    $(document).ready(function(){
      // when new payment proof is selected, add to list of proofs and render to DOM
      $(document).on('change', '#upload_proof', function(e){
        let invoiceNo = document.getElementById('invoice_no').value;
        let newFilesToUpload = $(this)[0].files;
        let fileToUpload = newFilesToUpload[0]; //.name;
        if(!fileToUpload) return; // don't do anything if no file is chosen
        // upload file via ajax
        let formData = new FormData();
        formData.append('upload_file', fileToUpload);
        formData.append('invoice_no', invoiceNo);
        $.ajax({
          method: "POST",
          url: "{!! route('proofs.upload') !!}",
          cache: false,
          contentType: false,//'multipart/form-data',
          processData: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: formData
        }).done(function(res){ // res: {success: true/false, imgPath: '/...', imgFullPath: 'http...', id: proofId}
          console.log(res);
          if(res.success){ // upload success
            // add preview proof item to DOM with proof image and remove button
            let imgList = document.getElementById('preview');
            let previewItem = document.createElement('div');
            previewItem.id = 'preview_item_' + res.id;
            previewItem.classList.add('preview_item');
            let proofImg = new Image(150, 150);
            proofImg.id = 'proof_img_' + res.id;
            proofImg.src = res.imgPath;
            proofImg.classList.add('proof_img');
            proofImg.alt = 'Image not found';
            let removeProof = document.createElement('a');
            removeProof.id = 'remove_proof_' + res.id;
            removeProof.classList.add('remove_proof');
            removeProof.href = '#';
            removeProof.innerText = "Remove";
            previewItem.appendChild(proofImg);
            previewItem.appendChild(removeProof);
            imgList.appendChild(previewItem);
          }else{ // upload unsuccessful
            alert(res.msg); // display message
          }
        }).fail(function(err){
          console.error(err);
          alert(err);
        });
      });

      // remove payment proof when remove button is clicked
      $(document).on('click', '.remove_proof', function(e){
        let removeId = $(this).attr('id').split('_').pop(); // eg. 3
        // Delete payment proof from backend via ajax
        let url = "{!! route('proofs.delete', 'placeholderId') !!}"; // url -> '/deleteurl/placeholderId'
        url = url.replace('placeholderId', removeId); // url -> '/deleteurl/3'
        $.ajax({
          url: url,
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }).done(function(res){ // res -> {success: true/false, msg: 'Delete success'/'Delete failed'}
          console.log(res);
          if(res.success){
            // remove corresponding proof preview display
            let previewItem = document.getElementById('preview_item_' + removeId);
            previewItem.parentNode.removeChild(previewItem);
          }else{ // delete proof failed
            alert(res.msg);
          }
        }).fail(function(err){
          console.error(err);
          alert(err);
        });
      });

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

      $('#customerModal').on('show.bs.modal', function(event){
        var modal = $(this);
      });

      // search and select customer with autocomplete functionality
      $('#customer_key').autocomplete({
        appendTo: "#customerModal .modal-body>.form-group",
        source: "{!! route('customers.autocomplete') !!}", // route for fetching customers JSON
        minLength: 3,
        select: function(event, ui){
          // change customer input fields with chosen customer's values
          $('#customer_key').val(ui.item.bill_name);
          $('#customer_id').val(ui.item.id);
          $('#bill_name').val(ui.item.bill_name);
          $('#bill_addr').val(ui.item.bill_addr);
          $('#bill_prov').val(ui.item.bill_prov);
          $('#bill_city').val(ui.item.bill_city);
          $('#bill_postal').val(ui.item.bill_postal);
          $('#ship_name').val(ui.item.ship_name);
          $('#ship_addr').val(ui.item.ship_addr);
          $('#ship_prov').val(ui.item.ship_prov);
          $('#ship_city').val(ui.item.ship_city);
          $('#ship_postal').val(ui.item.ship_postal);
          $('#customer_tel').val(ui.item.contact1_tel);
          $('#customer_fax').val(ui.item.fax);
          $('#customer_contact1').val(ui.item.contact1_firstname);
          $('#customer_contact2').val(ui.item.contact2_firstname);
          // close modal and trigger change of customer ship_prov value
          $('#customerModal').modal('hide');
          $('#ship_prov').change();
        }
      }).data("ui-autocomplete")._renderItem = function(ul, item){
        return $("<li class='ui-autocomplete-row'></li>")
          .data("item.autocomplete", item)
          .append("<p><b>Bill To: </b>"
            + item.bill_name
            + "</p><p><b>Location: </b>"
            + item.bill_city + ", " + item.bill_prov + ", " + item.bill_postal
            + "</p><p><b>Ship To: </b>"
            + item.ship_name
            + "</p><p><b>Location: </b>"
            + item.ship_city + ", " + item.ship_prov + ", " + item.ship_postal
            + "</p><p><b>Contact: </b>"
            + item.contact1_firstname + "(" + item.contact1_tel + ")"
            + "</p>")
          .appendTo(ul);
      };

      $('#orders_add').on('click', function(e){
        // add order item to end of orders array list
        let products = {!! json_encode($products->toArray()) !!}
        let newOrderId = $('tr.order-item').length;
        let productSel = $('<select id="orders_'+newOrderId+'_product_select" class="form-control product-select"></select>');
        productSel.append('<option id="orders_'+newOrderId+'_products_-1" value="" data-price="0" selected>Choose a Product</option>');
        products.forEach(function(product, prodIndex){
          productSel.append('<option id="orders_'+newOrderId+'_products_'+prodIndex+'" value="'+product.name+'" data-price="'+product.price+'">'+product.name+'</option>');
        });
        $('#orders_list').append('<tr class="order-item" id="orders_' + newOrderId + '"></tr>');
        $('#orders_' + newOrderId).append('<td></td>');
        $('#orders_' + newOrderId + ' td').append(productSel);
        $('#orders_' + newOrderId + ' td').append('<input type="text" name="orders['+newOrderId+'][product]"'
          + ' id="orders_'+newOrderId+'_product" class="form-control" value="">');
        $('#orders_' + newOrderId).append('<td><input type="number" name="orders['+newOrderId+'][price]"'
          + ' id="orders_'+newOrderId+'_price" class="form-control product-price"'
          + ' min="0" max="10000" step="0.01" value="0.00"></td>');
        $('#orders_' + newOrderId).append('<td><input type="number" name="orders['+newOrderId+'][quantity]"'
          + ' id="orders_'+newOrderId+'_quantity" class="form-control product-quantity"'
          + ' value="0"></td>');
        $('#orders_' + newOrderId).append('<td><input type="number" name="orders['+newOrderId+'][discount]"'
          + ' id="orders_'+newOrderId+'_discount" class="form-control product-discount"'
          + ' min="0" step="0.01" value="0.00"></td>');
        $('#orders_' + newOrderId).append('<td><input type="number" name="orders['+newOrderId+'][total]"'
          + ' id="orders_'+newOrderId+'_total" class="form-control product-total"'
          + ' step="0.01" value="0.00"></td>');
        $('#orders_' + newOrderId).append('<td><button type="button" id="orders_'+newOrderId+'_remove"'
          + ' class="btn btn-sm btn-danger orders-remove">Remove</button></td>');

        // trigger update on .product-total once order item added
        $('.product-total').change();
      });

      $(document).on('click', '.orders-remove', function(e){
        let numOrderItems = $('tr.order-item').length;
        if(numOrderItems <= 1){
          // do not allow remove item if it's the only order item
          alert('Invoice must contain at least one order item');
          return false;
        }
        let orderId = $(this).attr('id').split('_')[1];
        let orderSelector = '#orders_' + orderId;
        $(orderSelector).remove();

        // fix indices of orders array
        $('tr.order-item').each(function(index, elem){
          let oldOrderId = $(this).attr('id').split('_')[1];
          let newOrderId = 'orders_' + index;
          let newProdSelId = 'orders_' + index + '_product_select';
          let newProdId = 'orders_' + index + '_product';
          let newPriceId = 'orders_' + index + '_price';
          let newQuantityId = 'orders_' + index + '_quantity';
          let newDiscountId = 'orders_' + index + '_discount';
          let newTotalId = 'orders_' + index + '_total';
          let newRemoveId = 'orders_' + index + '_remove';

          $('#orders_' + oldOrderId + '_product').prop('name', 'orders['+index+'][product]');
          $('#orders_' + oldOrderId + '_price').prop('name', 'orders['+index+'][price]');
          $('#orders_' + oldOrderId + '_quantity').prop('name', 'orders['+index+'][quantity]');
          $('#orders_' + oldOrderId + '_discount').prop('name', 'orders['+index+'][discount]');
          $('#orders_' + oldOrderId + '_total').prop('name', 'orders['+index+'][total]');

          $('#orders_' + oldOrderId + '_product_select option').each(function(prodIndex, prodElem){
            let newProdItemIdArr = $(this).attr('id').split('_');
            newProdItemIdArr[1] = index;
            let newProdItemId = newProdItemIdArr.join('_');
            $(this).prop('id', newProdItemId);
          });
          $('#orders_' + oldOrderId + '_product_select').prop('id', newProdSelId);
          $('#orders_' + oldOrderId + '_product').prop('id', newProdId);
          $('#orders_' + oldOrderId + '_price').prop('id', newPriceId);
          $('#orders_' + oldOrderId + '_quantity').prop('id', newQuantityId);
          $('#orders_' + oldOrderId + '_discount').prop('id', newDiscountId);
          $('#orders_' + oldOrderId + '_total').prop('id', newTotalId);
          $('#orders_' + oldOrderId + '_remove').prop('id', newRemoveId);
          $('#orders_' + oldOrderId).prop('id', newOrderId);
        });

        // trigger totals change
        $('.product-total').change();
      });

      $(document).on('change', '.product-select', function(e){
        let prodSelectId = $(this).attr('id');
        let selectedProd = $(this).val();
        let orderId = prodSelectId.split('_')[1];
        let price = $('#' + prodSelectId + ' option:selected').data('price');
        $('#orders_' + orderId + '_product').val(selectedProd);
        $('#orders_' + orderId + '_price').val(price);
        $('#orders_' + orderId + '_price').change();
      });

      $(document).on('change', '.product-price', function(e){
        let price = $(this).val();
        if(!price || isNaN(price) || parseFloat(price, 2) < 0){
          price = 0;
          $(this).val(0);
        }else{
          price = parseFloat(price, 2);
          $(this).val(price.toFixed(2));
        }
        let orderId = $(this).attr('id').split('_')[1];
        let quantity = $('#orders_' + orderId + '_quantity').val();
        let discount = $('#orders_' + orderId + '_discount').val();
        let newTotal = price * parseInt(quantity) - parseFloat(discount, 2);
        $('#orders_' + orderId + '_total').val(newTotal.toFixed(2));
        $('#orders_' + orderId + '_total').change();
      });

      $(document).on('change', '.product-quantity', function(e){
        let quantity = $(this).val();
        if(!quantity || isNaN(quantity) || !Number.isInteger(Number(quantity))){
          quantity = 0;
          $(this).val(0);
        }else{
          quantity = parseInt(quantity);
        }
        let orderId = $(this).attr('id').split('_')[1];
        let price = $('#orders_' + orderId + '_price').val();
        let discount = $('#orders_' + orderId + '_discount').val();
        let newTotal = parseFloat(price, 2) * quantity - parseFloat(discount, 2);
        $('#orders_' + orderId + '_total').val(newTotal.toFixed(2));
        $('#orders_' + orderId + '_total').change();
      });

      $(document).on('change', '.product-discount', function(e){
        let discount = $(this).val();
        let orderId = $(this).attr('id').split('_')[1];
        let oldTotal = $('#orders_' + orderId + '_total').val();
        if(!discount || isNaN(discount) || parseFloat(discount, 2) < 0){
          discount = 0;
          $(this).val(0);
        }else if(parseFloat(discount, 2) > parseFloat(oldTotal, 2)){
          discount = parseFloat(oldTotal, 2);
          $(this).val(discount.toFixed(2));
        }else{
          discount = parseFloat(discount, 2);
          $(this).val(discount.toFixed(2));
        }
        let price = $('#orders_' + orderId + '_price').val();
        let quantity = $('#orders_' + orderId + '_quantity').val();
        let newTotal = parseFloat(price, 2) * parseInt(quantity) - discount;
        $('#orders_' + orderId + '_total').val(newTotal.toFixed(2));
        $('#orders_' + orderId + '_total').change();
      });

      // sum all totals for total amount
      // change total amount when total of any order changes
      $(document).on('change', '.product-total', function(e){
        let totalAmount = tallyTotals() * (1 + parseFloat($('#tax_rate').val(), 3) / 100) + parseFloat($('#freight').val(), 2);
        $('#total_amount').text(totalAmount.toFixed(2));
      });

      // change total amount when tax changes
      $(document).on('change', '#tax_rate', function(e){
        let taxRate = $(this).val();
        if(!taxRate || isNaN(taxRate) || parseFloat(taxRate, 3) < 0 || parseFloat(taxRate, 3) >= 100 ){
          taxRate = 0;
          $(this).val(0);
        }else{
          taxRate = parseFloat(taxRate, 3);
        }
        let totalAmount = tallyTotals() * (1 + taxRate/100) + parseFloat($('#freight').val(), 2);
        $('#total_amount').text(totalAmount.toFixed(2));
      });

      // change total amount when freight changes
      $(document).on('change', '#freight', function(e){
        let freight = $(this).val();
        if(!freight || isNaN(freight) || parseFloat(freight, 2) < 0){
          freight = 0;
          $(this).val(0);
        }else{
          freight = parseFloat(freight, 2);
        }
        let totalAmount = tallyTotals() * (1 + parseFloat($('#tax_rate').val(), 3) / 100) + freight;
        $('#total_amount').text(totalAmount.toFixed(2));
      });

      // change tax rates and descriptions when shipping prov changes
      $(document).on('change', '#ship_prov', function(e){
        let taxRate = $('#ship_prov option:selected').data('taxrate');
        let taxDescription = $('#ship_prov option:selected').data('taxdesc');
        $('#tax_rate').val(taxRate);
        $('#tax_info_rate').text(taxRate);
        $('#tax_description').val(taxDescription);
        $('#tax_info_desc').text(taxDescription);
        $('#tax_rate').change();
      });

      // save form progress
      $(document).on('click', '#save_progress', function(e) {
        let formEntries = $('#invoice_form').serializeArray()
          .filter(entry => entry.name != '_method');
        //console.log(formEntries);
        $.post("{!! route('invoices.save') !!}", formEntries)
          .done(function(res){ // progress saved successfully, res -> {status: 1, msg: 'Invoice saved successfully'}
            console.log(res);
            alert(res.msg);
          })
          .fail(function(err){
            console.error(err);
            alert(err);
          });
      });

      // delete from progress
      $(document).on('click', '#delete_progress', function(e) {
        $.ajax({
          method: "DELETE",
          url: "{!! route('invoices.delete_progress') !!}",
          data: {
            _token: $('input[name="_token"]').val()
          }
        }).done(function(res){
          console.log(res);
          $('#progress_alert').alert('close');
          alert('Progress deleted successfully');
        }).fail(function(err){
          console.error(err);
          alert('Unable to delete progress');
        });
      });
    });

  </script>
@endsection
