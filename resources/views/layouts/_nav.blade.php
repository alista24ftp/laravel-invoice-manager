<div class="col-3">
  <div id="main-menu" class="list-group">
    <a class="list-group-item {{active_class(active_nav_item('invoices'))}}" 
      href="{{route('invoices.index')}}">Invoices</a>
    <a class="list-group-item {{active_class(active_nav_item('customers'))}}" 
      href="{{route('customers.index')}}">Customers</a>
    <a class="list-group-item {{active_class(active_nav_item('sales'))}}" 
      href="{{route('sales.index')}}">Sales Reps</a>
    <a class="list-group-item {{active_class(active_nav_item('users') || active_nav_item('companies') || active_nav_item('products') || active_nav_item('shipping') || active_nav_item('terms') || active_nav_item('taxes') )}}"
          href="#sub-menu" data-toggle="collapse" data-parent="#main-menu">Settings<span class="caret"></span></a>
      <div class="collapse {{active_class((active_nav_item('users') || active_nav_item('companies') || active_nav_item('products') || active_nav_item('shipping') || active_nav_item('terms') || active_nav_item('taxes')), 'show')}} list-group-level1" id="sub-menu">
        <a class="list-group-item {{active_class(active_nav_item('users'))}}" href="{{route('users.edit')}}" data-parent="#sub-menu">User Profile</a>
        <a class="list-group-item {{active_class(active_nav_item('companies'))}}" href="{{route('companies.edit')}}" data-parent="#sub-menu">Company Info</a>
        <a class="list-group-item {{active_class(active_nav_item('products'))}}" href="{{route('products.index')}}" data-parent="#sub-menu">Product Info</a>
        <a class="list-group-item {{active_class(active_nav_item('shipping'))}}" href="{{route('shipping.index')}}" data-parent="#sub-menu">Shipping Options</a>
        <a class="list-group-item {{active_class(active_nav_item('terms'))}}" href="{{route('terms.index')}}" data-parent="#sub-menu">Payment Terms</a>
        <a class="list-group-item {{active_class(active_nav_item('taxes'))}}" href="{{route('taxes.index')}}" data-parent="#sub-menu">Tax Options</a>
      </div>
  </div>

  @include('users._logout')
</div>
