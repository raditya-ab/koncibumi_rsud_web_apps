<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zanzifa{

	public function sender($otp,$mobile_number){
		$userkey = '02434fff22c0';
		$passkey = '4428ffe69cc15796064c4ee6';
		$telepon = $mobile_number;
		$message = 'Hi, Please use this OTP '.$otp;
		$url = 'https://console.zenziva.net/reguler/api/sendsms/';
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_POST, 1);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
		    'userkey' => $userkey,
		    'passkey' => $passkey,
		    'to' => $telepon,
		    'message' => $message
		));
		$results = json_decode(curl_exec($curlHandle), true);
		error_log(json_encode($results));
		
		curl_close($curlHandle);
	}
}

?>