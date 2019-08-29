<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class RatesController extends Controller
{
    private function getRates()
    {
        $wsdl = 'http://api.cba.am/exchangerates.asmx?wsdl';
        $params = array('encoding' => 'UTF-8', 'soap_version' => 'SOAP_1_2', 'trace' => true);
        $client = new SoapClient($wsdl, $params);

        return $client;
    }


    public function get_rates_by_date()
    {
        $GetRates = $this->getRates();
        $url = 'ExchangeRatesByDate';
        $params = array(['date' => date('Y-m-d')]);

        $by_date = $GetRates->__soapCall($url, $params);
        dd($by_date->ExchangeRatesByDateResult->Rates->ExchangeRate);

        return $by_date;
    }


    public function get_rates_by_date_by_iso()
    {

    }

    public function get_rates_latest()
    {

    }

    public function get_rates_latest_by_iso()
    {

    }

    public function get_iso_codes($wsdl)
    {

    }

    public function index()
    {
    }
}
