<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('prep_multiple_upload')){
	function prep_multiple_upload($name)
	{
		$filesCount = count($_FILES[$name]['name']);

		if(!is_null($_FILES) && !empty($_FILES) && $filesCount > 0){
			$temp_upload = array();
			for($i = 0; $i < $filesCount; $i++)
			{
				// Creating temp variable to store each files
				$temp_upload[$name][$i] = array(
					'name' => $_FILES[$name]['name'][$i],
					'type' => $_FILES[$name]['type'][$i],
					'tmp_name' => $_FILES[$name]['tmp_name'][$i],
					'error' => $_FILES[$name]['error'][$i],
					'size' => $_FILES[$name]['size'][$i]
				);
			}

			$_FILES = $temp_upload;
		}
	}


}
if(!function_exists('generate_file_upload_error')){
	function generate_file_upload_error($filename, $message)
	{
		$html = "";
		$html .= "<p>";
		$html .= $filename." failed to upload: ".$message;
		$html .= "</p>";

		return $html;
	}
}
?>