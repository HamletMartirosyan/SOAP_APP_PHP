<?php

namespace App\Http\Controllers;

use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use DateInterval;
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
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $iso = $_POST['iso'];
            $date = $_POST['date'];
            $count = $_POST['count'];

            if ($iso != '' && $date != '' && $count != '') {
                $func = 'ExchangeRatesByDateByISO';
                $args = array(['date' => $date, 'ISO' => $iso]);

                $data = $this->getRates($func, $args);
                $result = $data->ExchangeRatesByDateByISOResult->Rates->ExchangeRate;
                $rate = $result->Rate;
                $amount = $result->Amount;

                $pattern = '/^([0-9]+)([\.]{0,1})([0-9]+)$/';
                $count = str_replace(',', '.', strval($count));

                if (preg_match($pattern, $count))
                    $converted_value = $this->convert_rate($count, $rate, $amount);
                else
                    $error = 'The value is not correct';
            }
            else {
                if($date == '')
                    $error = 'The date is empty';
                if ($count == '')
                    $error = 'The value is empty';

            }

        }

        $context = [
            'iso' => $iso,
            'date' => $date,
            'count' => $count,
            'iso_codes' => $iso_codes,
            'error' => $error,
            'converted_value' => $converted_value,
        ];

        return view('by_date_by_iso', $context);
    }

    public function get_rates_by_date()
    {
        $func = 'ExchangeRatesByDate';
        $args = array(['date' => $_GET['date']]);

        $data = $this->getRates($func, $args);
        $rates = $data->ExchangeRatesByDateResult->Rates->ExchangeRate;

        $result = array();
        foreach ($rates as $rate) {
            $result[$rate->ISO] = [
                'ISO' => $rate->ISO,
                'Amount' => $rate->Amount,
                'Rate' => $rate->Rate,
                'Difference' => $rate->Difference,
            ];
        }

        return json_encode($result);
    }

    public function view_chart_form()
    {
        return view('draw_graphic', ['iso_codes' => $this->get_iso_codes()]);
    }


    public function get_data_for_chart()
    {
        $start_date = strtotime($_GET['start_date']);
        $end_date = strtotime($_GET['end_date']);
        $iso = $_GET['iso'];
        $result = "Start date or end date is not defined";

        if (isset($start_date) and isset($end_date)) {
            $dates = [];
            for ($current_date = $start_date; $current_date <= $end_date; $current_date += (86400))
                array_push($dates, date('Y-m-d', $current_date));

            $func = 'ExchangeRatesByDateByISO';
            $result = array();

            foreach ($dates as $date) {
                $args = array([
                    'date' => $date,
                    'ISO' => $iso
                ]);
                $data = $this->getRates($func, $args);
                $rates = $data->ExchangeRatesByDateByISOResult->Rates->ExchangeRate;


                $rate = $rates->Rate / $rates->Amount;
                $diff = $rates->Difference;
                $result[$date] = $rate;

                /*$result[$date] = [
                    'ISO' => $rates->ISO,
                    'Amount' => $rates->Amount,
                    'Rate' => $rates->Rate,
                    'Difference' => $rates->Difference,
                ];*/
            }
        }
        return $result;
    }


    public
    function get_rates_latest()
    {

    }

    public
    function get_rates_latest_by_iso()
    {

    }

    public
    function get_iso_codes()
    {
        $func = 'ISOCodes';
        $args = array([]);

        $data = $this->getRates($func, $args);
        $result = $data->ISOCodesResult->string;

        return $result;
    }

    protected
    function convert_rate($count, $rate, $amount)
    {
        if ($amount != 0)
            return $count * $rate / $amount;
        else
            return "Amount is Null";
    }


    public
    function index()
    {
        return view('index');
    }
}
