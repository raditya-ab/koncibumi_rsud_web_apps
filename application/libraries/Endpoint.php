<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Endpoint{

	function call_endpoint($config,$url,$headers){
		$ch = curl_init($url);
  		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($ch, CURLOPT_HTTPGET, 1);
  		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'X-token: '.$headers['x-token'],
		    'Content-Type:'.$headers['Content-Type']
		));
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	function call_login($config,$url){
		$post = [
		    'api_key' => $config['api_key'],
		    'secret_key' => $config['secret_key']
		];

		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
}

?>