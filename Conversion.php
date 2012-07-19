<?php

class ConversorNumerico{
    
    
    /**
     * 
     * @param $cont, cadena a convertir
     * @param $formato, formato a utilizar
     *  
     */
    public static function conversion($cont, $formato){
        $valPos="{ABCDEFGHI";
        $valNeg="}JKLMNOPQR";
        $factor = 1;
        
        $c = substr($cont, strlen($cont) -1);
        $pos = strpos($valPos, $c);
        if($pos === FALSE){
            $pos = strpos($valNeg, $c);
            if($pos > 0 ) $factor = -1; // un caso es el IVA que viene como 01600...
        }
       if($pos > 0 ) {
           $str = "".str_replace($c, $pos, $cont);
       }else{
           $str = $cont;
       }
        
        // echo "Formato: ".$formato.PHP_EOL;
        $partes = explode(",", $formato); //separamos por coma
        
        $numPosicionesEnteros = (int) $partes[0];
        $numPosicionesDecimales = 0;
        if (count($partes) > 1) {
            $numPosicionesDecimales = (int) $partes[1];
        }    
        // echo "PosicionesEnteros:".$numPosicionesEnteros.PHP_EOL;
        // echo "PosicionesDecimales:".$numPosicionesDecimales.PHP_EOL;
        
        $valInt = (int) substr($str, 0, $numPosicionesEnteros);
        $valDec = (int) substr($str, strlen($str) - $numPosicionesDecimales, $numPosicionesDecimales);
        $valDec = $valDec / pow(10, $numPosicionesDecimales);
        
        $str = $factor * ($valInt + $valDec);
        $str = number_format($str, $numPosicionesDecimales);
    
        return $str;
    }
}



?>