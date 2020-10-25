<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('generateSeoURL')){
	function generateSeoURL($string, $wordLimit = 0){
	    $separator = '-';

	    if($wordLimit != 0){
			$wordArr = explode(' ', $string);
			$string  = implode(' ', array_slice($wordArr, 0, $wordLimit));
	    }

	    $quoteSeparator = preg_quote($separator, '#');

	    $trans = array(
			'&.+?;'                  => '',
			'[^\w\d _-]'             => '',
			'\s+'                    => $separator,
			'('.$quoteSeparator.')+' => $separator
	    );

	    $string = strip_tags($string);
	    foreach ($trans as $key => $val){
	        $string = preg_replace('#'.$key.'#i'.(UTF8_ENABLED ? 'u' : ''), $val, $string);
	    }

	    $string = strtolower($string);

	    return trim(trim($string, $separator));
	}
}

/* End of file urlcreation_helper.php */
/* Location: ./application/backend/helpers/urlcreation_helper.php */