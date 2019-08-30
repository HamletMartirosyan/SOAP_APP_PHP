<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class RatesController extends Controller
{
    private function getRates($func, $args)
    {
        $wsdl = 'http://api.cba.am/exchangerates.asmx?wsdl';
        $params = array(
            'encoding' => 'UTF-8',
            'soap_version' => 'SOAP_1_2',
            'trace' => true);
        $client = new SoapClient($wsdl, $params);
        $result = $client->__soapCall($func, $args);

        return $result;
    }

    public function get_rates_by_date_by_iso()
    {
        $iso = '';
        $count = '';
        $converted_value = '';
        $date = date('Y-m-d');
        $iso_codes = $this->get_iso_codes();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $iso = $_POST['iso'];
            $date = $_POST['date'];
            $count = $_POST['count'];

            $func = 'ExchangeRatesByDateByISO';
            $args = array(['date' => $date, 'ISO' => $iso]);

            $data = $this->getRates($func, $args);
            $result = $data->ExchangeRatesByDateByISOResult->Rates->ExchangeRate;
            $rate = $result->Rate;
            $amount = $result->Amount;

            $converted_value = $this->convert_rate($count, $rate, $amount);
        }

        $context = [
            'iso' => $iso,
            'date' => $date,
            'count' => $count,
            'iso_codes' => $iso_codes,
            'converted_value' => $converted_value,
        ];

        return view('by_date_by_iso', $context);
    }

    public function get_rates_by_date()
    {
        $func = 'ExchangeRatesByDate';
        $date = $_GET['date'];
        $args = array(['date' => $date]);

        $data = $this->getRates($func, $args);
        $result = $data->ExchangeRatesByDate->Rates->ExchangeRate;
        dd($result);

        return $result;
    }

    public function draw_graphic()
    {
        $date = $_GET['date'];
        $func = 'ExchangeRatesByDate';
        $args = array(['date' => $date]);

        $data = $this->getRates($func, $args);
        $result = $data->ExchangeRatesByDate->Rates->ExchangeRate;

        return $result;
    }


    public function get_rates_latest()
    {

    }

    public function get_rates_latest_by_iso()
    {

    }

    public function get_iso_codes()
    {
        $func = 'ISOCodes';
        $args = array([]);

        $data = $this->getRates($func, $args);
        $result = $data->ISOCodesResult->string;

        return $result;
    }

    protected function convert_rate($count, $rate, $amount)
    {
        if ($amount != 0)
            return $count * $rate / $amount;
        else
            return "Amount is Null";
    }


    public function index()
    {
        return view('index');
    }
}
