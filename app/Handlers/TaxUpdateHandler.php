<?php
namespace App\Handlers;

use App\Models\Tax;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class TaxUpdateHandler
{
    protected $canadianTaxApi = "http://api.salestaxapi.ca/v2/province/all";
    private $client;
    private $taxes;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->taxes = [];
    }

    public function getTaxes()
    {
        $request = new Request('GET', $this->canadianTaxApi);
        $promise = $this->client->sendAsync($request)->then(function($response){
            // successful response
            $resCode = $response->getStatusCode();
            $resBody = $response->getBody();
            $resBody->seek(0);
            $resContent = '';
            while(!$resBody->eof()){
                $resContent .= $resBody->read(1024);
            }
            $this->taxes = json_decode($resContent, true);
        }, function($exception){
            // request failed
            $this->taxes = null;
        });
        $promise->wait();
    }

    public function updateTaxes()
    {
        $this->getTaxes();
        if(!$this->taxes) return false; // fail
        foreach($this->taxes as $prov => $tax){
            $existingTax = Tax::where('province', $prov)->first();
            if($existingTax){
                // update tax info
                $existingTax->update([
                    'description' => strtoupper($tax['type']),
                    'rate' => number_format(floatval($tax['applicable']) * 100, 3)
                ]);
            }else{
                // add tax info
                Tax::create([
                    'province' => strtoupper($prov),
                    'description' => strtoupper($tax['type']),
                    'rate' => number_format(floatval($tax['applicable']) * 100, 3)
                ]);
            }
        }
        return true;
    }
}
