<?php

namespace App\Services;

use App\Models\BtcExchangeRate;
use Exception;
use Illuminate\Support\Facades\Mail;

class BitCoinService
{

    public $currency = "INR";

    public $apiUrl = "https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=BTC&to_currency=";

    public $apikey = "YZNKPWNW4IQYQTWC";

    public function __construct()
    {
        $this->currency = "INR";

        // Need to move this to env and load from config
        $this->apiUrl = "https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=BTC&to_currency=";

        // Need to move this to env and load from config
        $this->apikey = "YZNKPWNW4IQYQTWC";
    }

    public function getBitCoinPrice()
    {
        $currBtc = 0;
        try {
            $json = json_decode(file_get_contents($this->getUrl()), true);

            $currBtc = $json["Realtime Currency Exchange Rate"]["5. Exchange Rate"] ?? 0;

            $oldBtc = $this->getExistingBTCRate();

            if ($oldBtc && $oldBtc->rate != $currBtc) {
                Mail::raw('The price changed from ' . $oldBtc->rate . ' to ' . $currBtc . ' for currency ' . $this->currency, function ($message) {
                    $message->from(config('mail.from.address'), config('mail.from.name'));
                    $message->to(config('mail.btc_change_notification_email'));
                    $message->subject('Notification:  Currency Exchange Rate');
                });
            }

            // Add new rate to db 
            $btcRate = new BtcExchangeRate();
            $btcRate->rate = $currBtc;
            $btcRate->currency = $this->currency;
            $btcRate->save();
        } catch (Exception $e) {
            info($e->getMessage());
        }

        return $currBtc;
    }

    public function getUrl()
    {
        return $this->apiUrl . $this->currency . '&apikey=' . $this->apikey;
    }

    public function getExistingBTCRate()
    {
        return BtcExchangeRate::where('currency', $this->currency)->orderBy('created_at', 'desc')->first()->rate ?? 0;
    }
}
