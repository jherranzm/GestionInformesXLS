<?php

class ConversorNumerico{
    
    private static $DEBUG = false;
    
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
        if(self::$DEBUG) echo "PosicionesEnteros:".$numPosicionesEnteros.PHP_EOL;
        if(self::$DEBUG) echo "PosicionesDecimales:".$numPosicionesDecimales.PHP_EOL;
        
        $valInt = (int) substr($str, 0, $numPosicionesEnteros);
        if(self::$DEBUG) echo "valInt:".$valInt.PHP_EOL;
        $valDec = (int) substr($str, strlen($str) - $numPosicionesDecimales, $numPosicionesDecimales);
        if(self::$DEBUG) echo "valDec:".$valDec.PHP_EOL;
        $valDec = $valDec / pow(10, $numPosicionesDecimales);
        if(self::$DEBUG) echo "valDec:".$valDec.PHP_EOL;
        
        $str = $factor * ($valInt + $valDec);
        if(self::$DEBUG) echo "str:".$str.PHP_EOL;
        if($numPosicionesDecimales > 0) $str = number_format($str, $numPosicionesDecimales);
        if(self::$DEBUG) echo "str:".$str.PHP_EOL;
    
        return $str;
    }

    public static function conversorAMinutos($cont, $longitudFormato){
        // $cont tiene 9 o 13 dígitos, de los cuales horas son los 9 primeros
        
        
        $horas = (int) substr($cont, 0, $longitudFormato - 4);
        $minutos = (int) substr($cont, $longitudFormato - 4, 2);
        $segundos = (int) substr($cont, $longitudFormato - 2, 2);
        
        $minutosTotal = ($horas * 60) + $minutos + ($segundos/60);
        $minutosTotal = number_format($minutosTotal, 4);
        
        return $minutosTotal;
        
    }

    public static function conversorAHHMMSS($cont){
        // $cont tiene 9 o 13 dígitos, de los cuales horas son los 9 primeros
        
        
        $horas = substr($cont, 0, 2);
        $minutos = substr($cont, 2, 2);
        $segundos = substr($cont, 4, 2);
        
        return $horas.":".$minutos.":".$segundos;
        
    }
}



?>