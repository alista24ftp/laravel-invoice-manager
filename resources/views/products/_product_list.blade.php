@if (count($products))
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
        <tr>
          <td>{{$product->id}}</td>
          <td>{{$product->name}}</td>
          <td>${{number_format($product->price, 2)}}</td>
          <td>
            <a href="{{route('products.edit', $product->id)}}" class="btn btn-sm btn-info" role="button">
              <i class="far fa-edit"></i>
            </a>
            <form action="{{route('products.destroy', $product->id)}}" method="POST" class="d-inline-block"
              onsubmit="return confirm('Are you sure you want to delete this product?');">
              {{csrf_field()}}
              {{method_field('DELETE')}}
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="far fa-trash-alt"></i>
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <div class="bg-secondary text-white text-center p-3">
    No Products Found
  </div>
@endif
