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
        try {
            $GetRates = $this->getRates();
            $date = date('Y-m-d');

            $url = 'ExchangeRatesByDate';
            $params = ['date' => $date];
            dump($params);

            $by_date = $GetRates->__soapCall($url, $params);
            var_dump($by_date);
            return $by_date;

        } catch (\Exception $ex){
            var_dump($ex);
        }
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
