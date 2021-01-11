<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zanzifa{

	public function sender($otp = "",$mobile_number,$template_message = ""){
		$userkey = '02434fff22c0';
		$passkey = '4428ffe69cc15796064c4ee6';
		$telepon = $mobile_number;
		$message = 'Hi, Please use this OTP '.$otp;
		$url = 'https://console.zenziva.net/reguler/api/sendOTP/';

		if ( $otp == "" ){
			$message = $template_message;
			$url = 'https://console.zenziva.net/reguler/api/sendsms/';
		}
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_POST, 1);

		if ( $otp != "" ){
			curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
				'userkey' => $userkey,
				'passkey' => $passkey,
				'to' => $telepon,
				'kode_otp' => $otp
			));
		}else{
			echo  $url.','.$message;
			curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
			    'userkey' => $userkey,
			    'passkey' => $passkey,
			    'to' => $telepon,
			    'message' => $message
			));
		}
		$result = curl_exec($curlHandle);
		echo $result;
		curl_close($curlHandle);
		return json_decode($result);
		// $results = json_decode(curl_exec($curlHandle), true);
		// error_log(json_encode($results));
		
		// curl_close($curlHandle);
	}

	function sendNotif($to, $message){
 
	    $feilds = array('to'=>$to, 'notification'=>$message);

	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($feilds));
	    
	    $headers = array();
	    $headers[] = 'Authorization: Key= AAAATEboWT4:APA91bF4IKAMEnQ2pCs2GAFEiIjKw1BSYWfQ932ZiTRXeqS0kFXUM85QMAeifcsl8mzhy4nn1MzFeGt0ZoQxc7JlhUdatYYS1khzU_ckqGYgf8GdIt-4pPfckPCiMX1RyksI3D6vd4Im';
	    $headers[] = 'Content-Type: application/json';
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    
	    $result = curl_exec($ch);
	    if (curl_errno($ch)) {
	        echo 'Error:' . curl_error($ch);
	    }
	    curl_close($ch);

	    return true;
	}
}

?>