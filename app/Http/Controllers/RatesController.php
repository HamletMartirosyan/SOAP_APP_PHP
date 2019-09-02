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

    public function get_rates_by_date_by_iso(Request $request)
    {
        $iso = '';
        $count = '';
        $converted_value = '';
        $date = date('Y-m-d');
        $iso_codes = $this->get_iso_codes();
        $error = '';

        if ($request->isMethod('post')) {
            $iso = $request->input('iso');
            $date = $request->input('date');
            $count = $request->input('count');

            $request->validate([
                'date' => 'required|date',
                'count' => 'required|numeric',
                'iso' => 'required',
            ]);

            if ($iso != '' && $date != '' && $count != '') {
                $func = 'ExchangeRatesByDateByISO';
                $args = array(['date' => $date, 'ISO' => $iso]);

                $data = $this->getRates($func, $args);
                $result = $data->ExchangeRatesByDateByISOResult->Rates->ExchangeRate;

                $rate = $result->Rate;
                $amount = $result->Amount;

                $converted_value = $this->convert_rate($count, $rate, $amount);
            }
        }

        $context = [
            'iso' => $iso,
            'date' => $date,
            'count' => $count,
            'iso_codes' => $iso_codes,
            'converted_value' => $converted_value,
            'error' => $error,
        ];

        return view('by_date_by_iso', $context);
    }

    public function get_rates_by_date(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $func = 'ExchangeRatesByDate';
        $args = array(['date' => $request->input('date')]);

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


    public function get_data_for_chart(Request $request)
    {
        $start_date = strtotime($request->input('start_date'));
        $end_date = strtotime($request->input('end_date'));
        $iso = $request->input('iso');
        $result = '';

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'iso' => 'required',
        ]);

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
                $result[$date] = $rate;
            }
        }
        return $result;
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
