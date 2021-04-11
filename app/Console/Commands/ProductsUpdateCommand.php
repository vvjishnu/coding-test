<?php

namespace App\Console\Commands;

use App\Services\FetchProductsService;
use Illuminate\Console\Command;

class ProductsUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $obj = new FetchProductsService();
        $obj->fetch();
    }
}
