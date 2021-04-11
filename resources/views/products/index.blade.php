@extends('layouts.master')

@section('content')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<form class="form-inline" method="get">
    <div class="row align-items-center">
        <div class="col-md-9">
            <input name="search" id="search" class="form-control" placeholder="Search product by name" value="{{ $search }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-2 search-btn">Search</button>
            <a href="{{ route('products.show') }}">
                <button type="button" class="btn btn-danger mb-2 search-btn">Reset</button>
            </a>
        </div>
    </div>
</form>

@if ($products->count())
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Item Name</th>
            <th scope="col">Price in INR</th>
            <th scope="col">Price in BTC</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $key => $product)
        <tr>
            <th scope="row">{{ $key + $products->firstItem() }}</th>
            <td>{{ $product->name  }}</td>
            <td>{{ number_format((float)$product->price, 2, '.', '') }}</td>
            <td>{{ number_format((float)($product->price * $btcRate), 10, '.', '') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@else
<div> No results! </div>
@endif
@endsection