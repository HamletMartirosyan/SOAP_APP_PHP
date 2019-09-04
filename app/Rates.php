<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SoapClient;

class Rates extends Model
{
	static public function getRates($func, $args)
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
}
