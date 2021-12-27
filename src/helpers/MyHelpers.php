<?php 
namespace Shenole_project\helpers;


class MyHelpers{
	public static function includeWithVariables($filePath, $variables = array(), $print = true){
		$output = NULL;
		
		if(file_exists($filePath)){
			// Extract the variables to a local namespace
			extract($variables);

			// Start output buffering
			ob_start();

			// Include the template file
			include $filePath;

			// End buffering and return its contents
			$output = ob_get_clean();
		}
		if ($print) {
			print $output;
		}
		return $output;

	}

}