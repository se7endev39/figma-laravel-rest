<?php

namespace App\Utilities;

class Translator
{

    public static function get_language_key()
    {
		$function_name = "_lang";
		$save_mode = "array";

		$export = "";
		$dir = new \RecursiveDirectoryIterator(app_path('Http'));
		$dir2 = new \RecursiveDirectoryIterator(resource_path('views'));
		
		foreach (new \RecursiveIteratorIterator($dir) as $filename => $file) {
			if(! is_dir($file)){
			  $export .= file_get_contents($filename);   
			}
		}
		
		foreach (new \RecursiveIteratorIterator($dir2) as $filename => $file) {
			if(! is_dir($file)){
			  $export .= file_get_contents($filename);   
			}
		}

		$matches = array();
		$t = preg_match_all("/_lang(\(.*?)\)/s", $export, $matches);
		$result = array_unique($matches[0]);
		$key = array();
		
		foreach($result as $word){
			//For Single Quote
			$word = trim(str_replace("$function_name('","",$word));
			$word = trim(str_replace("')","",$word));
			
			//For Double Quote
			$word = trim(str_replace("$function_name(","",$word));
			$word = trim(str_replace(')',"",$word));
			$word = htmlentities(str_replace('&quot;','',$word));
			$word = htmlentities(str_replace('&nbsp;',' ',$word));
			$word = str_replace('&amp;','&',$word);
            
			if($save_mode == "array"){
				//$key = '$language["'.$word.'"] = "'.ucwords($word).'";'."\n";
				$key[htmlspecialchars_decode($word)] = htmlspecialchars_decode($word);
			}
		}
		
		return $key;
		
    }		

}