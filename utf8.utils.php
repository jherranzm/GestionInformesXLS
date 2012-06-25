<?php
/**
 * Recodifica un array en un array UTF-8
 * 
 * @param array $array
 * @return multitype:string
 */
function convertArrayKeysToUtf8(array $array) {
	$convertedArray = array();
	foreach($array as $key => $value) {
// 		error_log("value:".$value);
		if(is_array($value)){
			//$value = $this->convertArrayKeysToUtf8($value);
			$value = convertArrayKeysToUtf8($value);
		}else{
			if(!mb_check_encoding($value, 'UTF-8')) $value = utf8_encode($value);
		}
		$convertedArray[$key] = $value;
	}
	return $convertedArray;
}

?>