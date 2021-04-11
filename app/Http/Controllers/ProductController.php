<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\BitCoinService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $itemPerPage = 20;

    public function index(Request $request, BitCoinService $btc)
    {

        $q = Product::orderBy('id', 'desc');
        if ($request->has('search')) {
            $q->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $q->paginate($this->itemPerPage);
        if ($products->currentPage() > $products->lastPage()) {
            $products = $products->paginate($this->itemPerPage, ['*'], 'page', $products->lastPage());
        }

        if ($btcRate = $btc->getExistingBTCRate()) {
            $btcMultiplier = 1 / $btcRate;
        }
        return view('products.index')
            ->with('title', 'Products listing')
            ->with('perPage', $this->itemPerPage)
            ->with('products', $products)
            ->with('search', $request->search)
            ->with('btcRate', $btcMultiplier);
    }
}
