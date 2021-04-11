<?php

namespace App\Services;

use App\Models\Product;

use Exception;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class FetchProductsService
{
    public $products = [];
    public $productLimit = 100;
    public $crowlUrl = "";
    public $queryParam = "q";
    public $searchTerm = "";

    public function __construct()
    {
        $this->products = [];
        $this->productLimit = 100;
        $this->crowlUrl = "https://www.flipkart.com/search";
        $this->queryParam = "q";
        $this->searchTerm = "laptop";
    }

    public function fetch()
    {
        $page = 1;

        while (count($this->products) < $this->productLimit) {
            $this->crawlData($page);
            $page++;
        }
        $this->save();
    }

    public function crawlData(int $page)
    {
        try {
            $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

            $crawler = $client->request('GET', $this->crowlUrl . "?" . $this->queryParam . "=" . $this->searchTerm . "&page=" . $page);

            $crawler->filter('._2kHMtA')->each(function ($node) {
                if (count($this->products) < $this->productLimit) { // Check to limit products to exact limit value
                    $this->products[] = [
                        'name' => $node->filter('._4rR01T')->first()->text(),
                        'price' => filter_var($node->filter('._30jeq3')->first()->text(), FILTER_SANITIZE_NUMBER_FLOAT)
                    ];
                }
            });
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }

    public function save()
    {
        if (count($this->products)) {
            Product::insert($this->products);
        }
    }
}
