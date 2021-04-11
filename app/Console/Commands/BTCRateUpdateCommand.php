<?php

namespace App\Console\Commands;

use App\Services\BitCoinService;
use Illuminate\Console\Command;

class BTCRateUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BTCRate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update BTC rates';

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
        $obj = new BitCoinService();
        $obj->getBitCoinPrice();
    }
}
