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
              <input type="hidden" name="orders[{{$item_idx}}][product]"
                id="orders_{{$item_idx}}_product" class="form-control"
                value="{{$item->product}}">
              <p id="orders_{{$item_idx}}_product_name">{{$item->product ?? 'No Product Selected'}}</p>
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
      <input type="hidden" name="tax_rate" value="{{old('tax_rate', ($invoice->tax_rate ?? 0))}}" id="tax_rate" />
      <input type="hidden" name="tax_description"
        value="{{old('tax_description', $invoice->tax_description)}}" id="tax_description" />
      <div id="tax_info">
        <p>
          Tax: <span id="tax_info_rate">{{old('tax_rate', ($invoice->tax_rate ?? 0))}}</span>%
          (<span id="tax_info_desc">{{old('tax_description', ($invoice->tax_description ?? 'No Tax'))}}</span>)
        </p>
      </div>
      <div class="form-group row">
        <label class="col-2 col-form-label" for="freight">Freight</label>
        <div class="col-2">
          <input type="number" id="freight" name="freight" class="form-control"
            min="0" step="0.01" value="{{old('freight', ($invoice->freight ?? 0))}}" />
        </div>
      </div>
      <hr>
      <div id="final_total">
        <!-- Need to manually update this value whenever document loads -->
        <p>Amount: $<span id="total_amount"></span></p>
      </div>
    </div>
  </div>
</div>
