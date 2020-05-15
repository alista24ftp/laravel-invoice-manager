@if(count($invoices))
  <ul class="list-unstyled border mt-3">
    @foreach ($invoices as $invoice)
      <li class="media">
        <div class="media-body">
          <div class="media-heading mt-0 mb-1">
            <a href="{{route('invoices.show', $invoice->invoice_no)}}" title="{{$invoice->invoice_no}}">
              Invoice {{$invoice->invoice_no}} - {{$invoice->bill_name}}
            </a>
            @if($invoice->overdue())
              <span class="float-right overdue">
                Payment Overdue
              </span>
            @elseif($invoice->paid)
              <span class="float-right paid">
                Paid
              </span>
            @else
              <span class="float-right unpaid">
                Unpaid
              </span>
            @endif
          </div>
          <div class="media-body">
            <p>${{number_format($invoice->totalAmount(), 2)}}</p>
            <p>Contact: {{$invoice->customer_contact1}}</p>
            <p>Created: {{$invoice->create_date}}</p>
          </div>
          <small class="media-body meta text-secondary">
            <a href="{{route('invoices.edit', $invoice->invoice_no)}}" class="text-secondary">
              <i class="far fa-edit"></i>
              Edit
            </a>
            <span>|</span>
            <a href="{{route('invoices.duplicate', $invoice->invoice_no)}}" class="text-secondary">
              <i class="far fa-clipboard"></i>
              Duplicate
            </a>
          </small>
        </div>
      </li>
      @if(!$loop->last)
        <hr>
      @endif
    @endforeach
  </ul>
@else
  <div class="empty-block">
    No invoices found
  </div>
@endif
