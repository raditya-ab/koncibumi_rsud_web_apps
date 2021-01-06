<?php
	if(! defined('ENVIRONMENT') )
	{
		$domain = strtolower($_SERVER['HTTP_HOST']);

		switch($domain) {
            case '36.78.27.51':
            case '36.78.27.51:8187' :
                define('ENVIRONMENT', 'production');
                break;
            case 'koncibumi.wedotheeffin.work':
                define('ENVIRONMENT', 'staging');
                break;
			default :
                define('ENVIRONMENT', 'development');
                break;
		}
	}
?>