@extends('layouts.app')
@section('title', 'Invoice Create')

@section('content')
  <form id="invoice_form" action="{{route('invoices.store')}}" method="POST" enctype="multipart/form-data">
    @include('shared._error')
    {{csrf_field()}}
    <input type="hidden" id="op" name="op" value="create" />

    @foreach ($invoice->paymentProofs as $idx => $proof)
      <input id="proofs_{{$idx}}" class="proofs" type="hidden" name="proofs[{{$idx}}][path]" value="{{$proof->path}}" />
    @endforeach

    @include('invoices._saved_progress_reminder')

    <!-- INVOICE INFO -->
    @include('invoices._invoice_info', compact('invoice', 'company', 'sales_reps', 'payment_terms', 'shipping_options'))

    <!-- COMPANY INFO -->
    @include('invoices._company_info', ['invoice' => $invoice, 'company' => $company])

    <!-- CUSTOMER INFO -->
    @include('invoices._customer_info', compact('invoice', 'provinces', 'taxes'))

    <!-- ORDER INFO -->
    @include('invoices._order_info', ['invoice' => $invoice, 'products' => $products])

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

    // Calculate total amount after taxes and shipping
    function calculateTotalAmount(){
      let totalAmount = tallyTotals() * (1 + parseFloat($('#tax_rate').val(), 3) / 100) + parseFloat($('#freight').val(), 2);
      $('#total_amount').text(totalAmount.toFixed(2));
    }

    // Determine whether view is set in edit/create mode
    function getOp(){
      return document.getElementById('op').value; // 'edit' or 'create'
    }

    // Check to see if invoice is overdue
    function isOverdue()
    {
      let isPaid = parseInt(document.getElementById('paid').value); // eg. 0 or 1
      let createDate = document.getElementById('create_date').value; // eg. '2019-05-31'
      let termPeriod = parseInt(document.getElementById('terms_period').value); // eg. null or 30
      if(isPaid || !createDate || !termPeriod) return false;
      let createDateParts = createDate.split('-'); // eg. [2019, 05, 31]
      createDateParts[1]--; // month in JS date is 0-indexed
      let createDt = new Date(createDateParts[0], createDateParts[1], createDateParts[2]);
      let dueDt = new Date(createDt.getFullYear(), createDt.getMonth(), createDt.getDate() + termPeriod);
      let today = new Date();
      let todayDt = new Date(today.getFullYear(), today.getMonth(), today.getDate());
      return todayDt >= dueDt;
    }

    // Set payment status
    function setPaymentStatus()
    {
      let payStatus = document.getElementById('pay_status');
      let payStatusDisplay = document.getElementById('pay_status_display');
      let isPaid = parseInt(document.getElementById('paid').value);
      let overdue = isOverdue();
      payStatus.textContent = isPaid ? 'PAID' : (overdue ? 'OVERDUE' : 'UNPAID');
      payStatus.classList.add(isPaid ? 'paid' : 'unpaid');
      payStatus.classList.remove(isPaid ? 'unpaid' : 'paid');
      payStatusDisplay.textContent = isPaid ? 'PAID' : (overdue ? 'OVERDUE' : 'UNPAID');
      payStatusDisplay.classList.add(isPaid ? 'paid' : 'unpaid');
      payStatusDisplay.classList.remove(isPaid ? 'unpaid' : 'paid');
    }

    // DOM rendered
    $(document).ready(function(){
      calculateTotalAmount();
      if(getOp() == 'create'){
        document.getElementById('invoice_no').removeAttribute('readonly');
      }
      // initialize variables upon DOM render
      var uploadedItems = document.getElementsByClassName('proofs'); // track items of uploaded payment proofs
      var proofPaths = []; // proof image paths
      for(let i=0; i<uploadedItems.length; i++){
        proofPaths.push(uploadedItems[i].value); // get proof image paths
      }
      var formElement = document.getElementById('invoice_form');
      /*
      // already taken care of by blade foreach directive at the top of page
      proofPaths.forEach(function(path, index){
        // create hidden input item for each uploaded proof
        let inputProof = document.createElement('input');
        inputProof.id = 'proofs_' + index;
        inputProof.classList.add('proofs');
        inputProof.type = 'hidden';
        inputProof.name = 'proofs[' + index + '][path]';
        inputProof.value = path;
        // add input item to form
        formElement.appendChild(inputProof);
      });*/

      // when new payment proof is selected, add to list of proofs and render to DOM
      $(document).on('change', '#upload_proof', function(e){
        let newFilesToUpload = $(this)[0].files;
        let fileToUpload = newFilesToUpload[0]; //.name;
        if(!fileToUpload) return; // don't do anything if no file is chosen
        // upload file via ajax
        let formData = new FormData();
        formData.append('upload_file', fileToUpload);
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
        }).done(function(res){ // res: {success: true/false, imgPath: '/...', imgFullPath: 'http...'}
          console.log(res);
          if(res.success){ // upload success
            // add input item to form
            let inputProof = document.createElement('input');
            let index = proofPaths.length;
            inputProof.id = 'proofs_' + index;
            inputProof.classList.add('proofs');
            inputProof.type = 'hidden';
            inputProof.name = 'proofs[' + index + '][path]';
            inputProof.value = res.imgPath;
            formElement.appendChild(inputProof);

            // add newly uploaded image path to proofPaths
            proofPaths.push(res.imgPath);

            // create HTML elements for displaying uploaded image
            let imgList = document.getElementById('preview');
            let proofItem = document.createElement('div');
            proofItem.id = 'preview_item_' + index;
            proofItem.classList.add('preview_item');
            let img = new Image(150, 150);
            img.id = 'proof_img_' + index;
            img.classList.add('proof_img');
            img.alt = 'Image not found';
            img.src = res.imgPath;//imgFullPath;
            let removeBtn = document.createElement('a');
            removeBtn.href = '#';
            removeBtn.id = 'remove_proof_' + index;
            removeBtn.classList.add('remove_proof');
            removeBtn.innerText = "Remove";
            // add HTML elements to DOM
            proofItem.appendChild(img);
            proofItem.appendChild(removeBtn);
            imgList.appendChild(proofItem);

            console.log(proofPaths);
          }else{
            alert(res.msg); // display error message
          }
        }).fail(function(err){
          console.error(err);
          alert('Unable to upload image');
        });

      });

      // when remove proof button is clicked, remove proof from backend and DOM
      $(document).on('click', '.remove_proof', function(e){
        let proofId = $(this).attr('id'); // eg. remove_proof_1
        let index = proofId.split('_').pop(); // eg. 1
        let imgToRemove = proofPaths[index]; // eg. /uploads/images/temp/1234567890_abcdefghij.png
        // remove from backend
        $.ajax({
          method: 'DELETE',
          url: "{!! route('proofs.delete_temp') !!}",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            path: imgToRemove
          }
        }).done(function(res){ // res: {success: true/false, msg: 'Delete success'/'Delete failed'}
          console.log(res);
          if(res.success){ // Delete successful
            // Remove proof from proofPaths
            proofPaths.splice(index, 1);

            // Remove corresponding input element and update other proof input elements' ids and names
            let inputToRemove = document.getElementById('proofs_' + index);
            formElement.removeChild(inputToRemove);
            let proofInputs = formElement.getElementsByClassName('proofs');
            for(let i=0; i<proofInputs.length; i++){
              let inputIdParts = proofInputs[i].id.split('_'); // eg. ['proofs', '2']
              let inputIndex = inputIdParts.pop(); // eg. 2, inputIdParts -> ['proofs']
              if(inputIndex > index){
                // update element's id and name
                inputIdParts.push(inputIndex-1); // inputIdParts -> ['proofs', 1]
                proofInputs[i].id = inputIdParts.join('_'); // eg. proofs_1
                proofInputs[i].name = 'proofs[' + (inputIndex - 1) + '][path]'; // eg. proofs[1][path]
              }
            }

            // Remove corresponding image from display and update other images' ids
            let itemToRemove = document.getElementById('preview_item_' + index);
            itemToRemove.parentNode.removeChild(itemToRemove);
            let displayItems = document.getElementsByClassName('preview_item');
            for(let i=0; i<displayItems.length; i++){
              let itemIndex = displayItems[i].id.split('_').pop();
              if(itemIndex > index){
                // update element's id
                displayItems[i].id = 'preview_item_' + (itemIndex - 1);
                // update img element id
                let imgEl = document.getElementById('proof_img_' + itemIndex);
                imgEl.id = 'proof_img_' + (itemIndex - 1);
                // update remove element id
                let removeProofEl = document.getElementById('remove_proof_' + itemIndex);
                removeProofEl.id = 'remove_proof_' + (itemIndex - 1);
              }
            }
          }else{
            console.error(res.msg);
            alert(res.msg);
          }
        }).fail(function(err){
          console.error(err);
          alert('Unable to delete image');
        });
      });

      // toggle payment status
      $(document).on('change', '#paid', function(e){
        let oldPayStatus = parseInt($(this).val());
        let newPayStatus = oldPayStatus == 1 ? 0 : 1;
        $(this).val(newPayStatus);
        setPaymentStatus();
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
        $('#orders_' + newOrderId + ' td').append('<input type="hidden" name="orders['+newOrderId+'][product]"'
          + ' id="orders_'+newOrderId+'_product" class="form-control" value="">');
        $('#orders_' + newOrderId + ' td').append('<p id="orders_' + newOrderId + '_product_name">No Product Selected</p>');
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
          let newProdNameId = 'orders_' + index + '_product_name';
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
          $('#orders_' + oldOrderId + '_product_name').prop('id', newProdNameId);
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
        $('#orders_' + orderId + '_product_name').text(selectedProd ? selectedProd : 'No Product Selected');
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
        calculateTotalAmount();
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

      // change payment status when create data changes
      $(document).on('change', '#create_date', function(e){
        setPaymentStatus();
      });

      // change sales rep display when a different sales rep is selected
      $(document).on('change', '#sales_rep_select', function(e){
        let newSalesRep = $('#sales_rep_select option:selected').val();
        $('#sales_rep').val(newSalesRep);
        $('#sales_rep').change();
        if(!newSalesRep) newSalesRep = '(None)';
        $('#sales_rep_display').text(newSalesRep);
      });

      // change terms info when terms change
      $(document).on('change', '#terms_select', function(e){
        let newTerms = $('#terms_select option:selected').val();
        let newTermsPeriod = $('#terms_select option:selected').data('period');
        let newTermsContent = $('#terms_select option:selected').text();
        $('#terms').val(newTerms);
        $('#terms').change();
        $('#terms_period').val(newTermsPeriod);
        $('#terms_period').change();
        $('#terms_display').text(newTermsContent);
        setPaymentStatus();
      });

      // change shipping option when a different via is selected
      $(document).on('change', '#via_select', function(e){
        let newVia = $('#via_select option:selected').val();
        $('#via').val(newVia);
        $('#via').change();
        if(!newVia) newVia = 'N/A';
        $('#via_display').text(newVia);
      });

      // save form progress
      $(document).on('click', '#save_progress', function(e) {
        let formEntries = $('#invoice_form').serializeArray()
          .filter(entry => entry.name != '_method');
        //console.log(formEntries);
        $.post("{!! route('invoices.save') !!}", formEntries)
          .done(function(res){ // res -> {status: 1/2, msg: '...', proofs: [{path: '...', index: 0}], failed_proofs: [{path: '...', index: 1}]}
            console.log(res);
            // Update all proof paths
            if(res.proofs){
              res.proofs.forEach(function(proof){
                // Update corresponding entry in proofPaths array
                proofPaths[proof.index] = proof.path;
                // Update corresponding hidden proof input value
                let proofInput = document.getElementById('proofs_' + proof.index);
                proofInput.value = proof.path;
                // Update corresponding preview item image src
                let proofImg = document.getElementById('proof_img_' + proof.index);
                proofImg.src = proof.path;
              });
            }

            if(res.status == 2){ // Invoice saved but some proofs were not saved successfully
              // Delete all failed proofs
              let failedIndices = res.failed_proofs.map(function(proof){
                return proof.index;
              });
              failedIndices.forEach(function(index){
                // Remove corresponding proof path from proofPaths array
                proofPaths[index] = undefined;
                // Delete corresponding hidden proof input
                let failedProofInput = document.getElementById('proofs_' + index);
                formElement.removeChild(failedProofInput);
                // Delete corresponding preview item
                let failedProofItem = document.getElementById('preview_item_' + index);
                failedProofItem.parentNode.removeChild(failedProofItem);
              });
              proofPaths = proofPaths.filter(function(path){
                return path !== undefined;
              });

              // Update ids and names of remaining proof elements
              let proofInputs = formElement.getElementsByClassName('proofs');
              let proofItems = document.getElementsByClassName('preview_item');
              let proofImgs = document.getElementsByClassName('proof_img');
              let proofRemoveBtns = document.getElementsByClassName('remove_proof');
              for(let i=0; i<proofPaths.length; i++){
                proofInputs[i].id = 'proofs_' + i;
                proofInputs[i].name = 'proofs[' + i + '][path]';
                proofInputs[i].value = proofPaths[i];
                proofItems[i].id = 'preview_item_' + i;
                proofImgs[i].id = 'proof_img_' + i;
                proofImgs[i].src = proofPaths[i];
                proofRemoveBtns[i].id = 'remove_proof_' + i;
              }
            }
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
