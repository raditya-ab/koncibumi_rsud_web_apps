<?php
	if(! defined('ENVIRONMENT') )
	{
		$domain = strtolower($_SERVER['HTTP_HOST']);

		switch($domain) {
            // case 'n-bri.org':
            // case 'www.n-bri.org' :
            //     define('ENVIRONMENT', 'production');
            //     break;
            case 'koncibumi.raditya.site':
            case 'koncibumi.wedotheeffin.work':
            case '36.78.27.51' :
                define('ENVIRONMENT', 'staging');
                break;
			default :
                define('ENVIRONMENT', 'development');
                break;

		}
	}
?>