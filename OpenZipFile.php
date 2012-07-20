<?php

require_once("Clave.php");
require_once('Conversion.php');


define(DIRECTORY_SEPARATOR, '/');

$zipDir = getcwd() . DIRECTORY_SEPARATOR;

echo $zipDir.PHP_EOL;


$file = "/Users/jherranzm/Downloads/Fichero_Ejemplo.zip";

$zip = zip_open($file);

$clavesA = loadClaves();
$claves901000 = loadClaves901000();
$claves903000 = loadClaves903000();
$claves00 = loadClaves000000();
$estructuras = array();

if(!$zip){
    echo "ERROR! No se ha podido abrir el fichero ".$file.PHP_EOL;
}else{
    
    // Leemos todos los ficheritos que haya en el Zip, aunque en teoría sólo hay uno...!
    while ($resource = zip_read($zip)) {
        echo "Nombre:               " . zip_entry_name($resource) . PHP_EOL;
        echo "Tamaño actual del fichero:    " . zip_entry_filesize($resource) . PHP_EOL;
        echo "Tamaño comprimido:    " . zip_entry_compressedsize($resource) . PHP_EOL;
        echo "Método de compresión: " . zip_entry_compressionmethod($resource) . PHP_EOL;
        $completeName = $zipDir . zip_entry_name($resource);
        echo "Nombre completo del fichero:".$completeName.PHP_EOL;

        if (zip_entry_open($zip, $resource, "r")) {
            
            $ret = saveUnzippedFile($resource, $completeName);
            
            $lineas = file($completeName);
            
            $estructuras = getEstructura($lineas, $clavesA, $claves00, $claves901000, $claves903000);

            procesaFichero977R($lineas, $estructuras);
            
            zip_entry_close($resource);
        }
        echo PHP_EOL;
    }
    zip_close($zip);
}

function init(){
}

function procesaLinea($linea, $clavesA){
    
    $str = "";
    foreach ($clavesA as $key => $value) {
        //echo "".$value->longitud."\t".$value->posicion.PHP_EOL;
        $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
    }
    echo $str.PHP_EOL;
}

/**
 * 
 * @param $linea, la línea a tratar
 * @param $clavesA, la lista de campos en la zonaA (comunes)
 * @param $claves00, las claves específicas del registro 00.00.00
 */
function procesaLinea000000($linea, $clavesA, $claves00){
    
    $str = "";

    foreach ($clavesA as $key => $value) {
        $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
    }
    
    foreach ($claves00 as $key => $value) {
        $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
    }
    echo $str.PHP_EOL;
    
}

/**
 * 
 * 
 * 
 * 
 */
function procesaLinea901000($linea, $clavesA, $claves901000){
    
    $str = "";
    $longitudRegistro = 0;

    foreach ($clavesA as $key => $value) {
        //echo "".$value->longitud."\t".$value->posicion.PHP_EOL;
        $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
        $longitudRegistro = (int) substr($linea, $value->posicion - 1, $value->longitud);
    }
    echo $str.PHP_EOL;
    
    $str = "";
    $pos = 0;
    echo "Longitud Registro: ".$longitudRegistro.PHP_EOL;
    $repeticiones = (int) substr($linea, $claves901000[0]->posicion - 1, $claves901000[0]->longitud);
    echo "Repeticiones: ".$repeticiones.PHP_EOL;
    $numPos = (int) substr($linea, $claves901000[1]->posicion - 1, $claves901000[1]->longitud);
    echo "Numero Posiciones: ".$numPos.PHP_EOL;
    
    $claves901000[5]->longitud = $numPos;
    
    $pos = $claves901000[1]->posicion + $claves901000[1]->longitud - 1;
    echo "Posición: ".$pos.PHP_EOL;
    
    for($index = 0; $index < $repeticiones; $index++){
        echo "".PHP_EOL;
        for ($k = 2; $k < 6 ; $k++) {
            // //echo "".$value->longitud."\t".$value->posicion.PHP_EOL;
            $_long = $claves901000[$k]->longitud;
            $posF = $pos + $_long;
            if($longitudRegistro < $posF){ // Nos pasamos...
                $_long = $longitudRegistro - $pos;
            }
            $_txt = utf8_encode(trim(substr($linea, $pos, $_long)));
            $str .= $_txt.";";
            $pos += $_long;
            echo $claves901000[$k]->campo."\t#".$_txt."#".PHP_EOL;
        }
        //echo "Posición: ".$pos.PHP_EOL;
        
    }
    //echo $str.PHP_EOL;
    
    
}

/**
 * 
 * Las líneas 903000 contienen la estructura de cada tipo de registro, 
 * con ellas se puede leer cada uno de los registros.
 * 
 * 
 * 
 * return campos
 */
function procesaLinea903000($linea, $clavesA, $claves903000){
    
    $campos = array();
    
    $str = "";
    $longitudRegistro = 0;

    foreach ($clavesA as $key => $value) {
        //echo "".$value->longitud."\t".$value->posicion.PHP_EOL;
        $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
        $longitudRegistro = (int) substr($linea, $value->posicion - 1, $value->longitud);
    }
    echo $str.PHP_EOL;
    
    $str = "";
    $pos = 0;
    
    echo "Longitud Registro: ".$longitudRegistro.PHP_EOL;
    
    $codigoRegistro = substr($linea, $claves903000[0]->posicion - 1, $claves903000[0]->longitud);
    echo "Codigo Registro: ".$codigoRegistro.PHP_EOL;
    
    $numeroBloques = (int) substr($linea, $claves903000[1]->posicion - 1, $claves903000[1]->longitud);
    echo "Número de Bloques: ".$numeroBloques.PHP_EOL;
    
    $numeroCampos = (int) substr($linea, $claves903000[2]->posicion - 1, $claves903000[2]->longitud);
    echo "Número de Campos: ".$numeroCampos.PHP_EOL;
    
    $pos = $claves903000[2]->posicion + $claves903000[2]->longitud - 1;
    
    for($kb = 0; $kb < $numeroBloques; $kb++){
        
        $numeroBloque = (int) substr($linea, $pos, $claves903000[3]->longitud);
        $pos += (int) $claves903000[3]->longitud;
        
        $numeroBloquePadre = (int) substr($linea, $pos, $claves903000[4]->longitud);
        $pos += (int) $claves903000[4]->longitud;
        
        echo PHP_EOL.PHP_EOL;
        echo "Numero de Bloque:".$numeroBloque.PHP_EOL;
        echo "Numero de Bloque Padre:".$numeroBloquePadre.PHP_EOL;
        
        for($kc = 0; $kc < $numeroCampos; $kc++){
                
            echo PHP_EOL.PHP_EOL;
            $d = ""; $t = ""; $f = ""; $p = 0; $l = 0; $r = 0; $ta = "";
            for($i = 5; $i < 12; $i++){
                $_txt = substr($linea, $pos, $claves903000[$i]->longitud);
                $pos += (int) $claves903000[$i]->longitud;
                
                if($claves903000[$i]->formato == "N"){
                    echo $pos.":".$longitudRegistro."\t\t".$claves903000[$i]->campo." : ".(int)$_txt.PHP_EOL;
                }else{
                    echo $pos.":".$longitudRegistro."\t\t".$claves903000[$i]->campo." : ".trim($_txt).PHP_EOL;
                } //if
                
                switch ($i) {
                    case 5: $d = str_replace(" ", "_", trim($_txt)); break;
                    case 6: $t = trim($_txt); break;
                    case 7: $f = trim($_txt); break;
                    case 8: $p = (int) $_txt; break;
                    case 9: $l = (int) $_txt; break;
                    case 10: $r = (int) $_txt; break;
                    case 11: $ta = trim($_txt); break;
                    
                    default:
                        
                        break;
                } //switch
            }//for
            
            if($d == ""){
            }else if($d == "OCURRENCIAS" && $l == 0){
            }else{
                $campos[] = new Campo($codigoRegistro, $numeroBloque, $numeroBloquePadre, $d, $t, $f, $p, $l, $r, $ta);
            }
            
        }//for
    }//for
    
    //print_r($campos);
    
    return $campos;
    
    
}

/**
 * 
 * 
 * 
 * 
 * 
 * 
 */
function loadClaves(){
    $claves = array();

    $claves[] = new Clave("A1", "CODIGO DE REGISTRO",               6, "N", 1);
    $claves[] = new Clave("A2", "NUMERO SECUENCIAL",                8, "N", 7);
    $claves[] = new Clave("A3", "CODIGO CLIENTE",                   8, "N", 15);
    $claves[] = new Clave("A4", "AGRUPACION FACTURABLE",           12, "A", 23);
    $claves[] = new Clave("A5", "AGRUPACION PARA DETALLE",         12, "A", 35);
    $claves[] = new Clave("A6", "TIPO SERVICIO",                   12, "A", 47);
    $claves[] = new Clave("A7", "MULTICONEXION",                   20, "A", 59);
    $claves[] = new Clave("A8", "NUMERO COMERCIAL 1",              20, "A", 79);
    $claves[] = new Clave("A9", "NUMERO COMERCIAL 2",              20, "A", 99);
    $claves[] = new Clave("A10", "LONGITUD REGISTRO",               4, "N", 119);
    
    return $claves;
}

/**
 * 
 * 
 * 
 * 
 * 
 * 
 */
function loadClaves901000(){
    $claves = array();

    $claves[] = new Clave("C1", "NUMERO DE REPETICIONES",           3, "N", 123);
    $claves[] = new Clave("C2", "NUMERO DE POSICIONES",             3, "N", 126);
    $claves[] = new Clave("C11", "CODIGO EXTERNO",                  3, "A", 0);
    $claves[] = new Clave("C12", "CODIGO INTERNO",                 14, "A", 0);
    $claves[] = new Clave("C13", "CODIGO DE EQUIPO NO RENOVADO",    5, "A", 0);
    $claves[] = new Clave("C14", "DESCRIPCION",                   180, "A", 0);
    
    return $claves;
}

/**
 * 
 * 
 * 
 * 
 */
function loadClaves903000(){
    $claves = array();

    $claves[] = new Clave("C1", "CODIGO REGISTRO",                  6, "A", 123);
    $claves[] = new Clave("C2", "NUMERO DE BLOQUES",                3, "N", 129);
    
    $claves[] = new Clave("C20", "NUMERO DE CAMPOS",                3, "N", 132);
    $claves[] = new Clave("C21", "NUMERO DE BLOQUE",                1, "N", 0);
    $claves[] = new Clave("C22", "NUMERO DE BLOQUE PADRE",          1, "N", 0);
            
    $claves[] = new Clave("C231", "DESCRIPCION DE CAMPO",          25, "A", 0);
    $claves[] = new Clave("C232", "TIPO CAMPO",                     1, "A", 0);
    $claves[] = new Clave("C233", "FORMATO CAMPO",                 15, "A", 0);
    $claves[] = new Clave("C234", "POSICION CAMPO",                 4, "N", 0);
    $claves[] = new Clave("C235", "LONGITUD CAMPO",                 3, "N", 0);
    $claves[] = new Clave("C236", "INDICADOR CAMPO REPETIDO",       1, "N", 0);
    $claves[] = new Clave("C237", "CODIGO EXTERNO TABLA AUXILIAR",  3, "A", 0);
    
    return $claves;
}


function procesaRegistro($linea, $campos){
    $pos = 0; // donde estamos...
    $repeticionesBloque2 = 0;
    $longitudRegistro = 0;
    
    // Recorremos el primer bloque. Este NO se repite
    $strBloque1 = "";
    foreach ($campos as $key => $campo) {
        if($campo->numeroBloque == 1){
            $_txt = substr($linea, $pos, $campo->longitud);
            $pos += $campo->longitud;
            
            if($campo->descripcion == "OCURRENCIAS"){
                $repeticionesBloque2 = (int) $_txt;
             }else if($campo->descripcion == "LONGITUD_REGISTRO"){
                $longitudRegistro = (int) $_txt;
             }
             
             if($campo->tipo == "I"){ // Numérico
                 $_txt = ConversorNumerico::conversion($_txt, $campo->formato);
             }
             $strBloque1 .= $_txt.";";
        }
        
    }
    // echo $strBloque1.PHP_EOL;
    
    for($k = 0; $k < $repeticionesBloque2; $k++){
        // echo "".PHP_EOL;
        $strBloque2 = "";
         foreach ($campos as $key => $campo) {
            if($campo->numeroBloque == 2){
                $_txt = substr($linea, $pos, $campo->longitud);
                 if($campo->tipo == "I" || $campo->tipo == "N"){ // Numérico
                     $_txt = ConversorNumerico::conversion($_txt, $campo->formato);
                 }
                $strBloque2 .= $_txt.";";
                $pos += $campo->longitud;
            }
         }
         if($pos > $longitudRegistro){
             $k = $repeticionesBloque2 + 1;
         }
         echo $strBloque1.$strBloque2.PHP_EOL;
    }
}

function loadClaves000000(){
    $claves = array();

    $claves[] = new Clave("B1", "TIPO DE FACTURACION",          40, "A", 123);
    $claves[] = new Clave("B2", "CIF SUPRACLIENTE",             18, "A", 163);
    $claves[] = new Clave("B3", "NOMBRE SUPRACLIENTE",          70, "A", 181);
    $claves[] = new Clave("B4", "IDENTIFICACION TIPO ACUERDO",  30, "A", 251);
    $claves[] = new Clave("B5", "NUM AGF MONOSERVICIO",         20, "A", 281);
    $claves[] = new Clave("B6", "DUPLICADO-REGULAR",             5, "A", 301);
    $claves[] = new Clave("B7", "FECHA DE EMISION",              8, "N", 306);
    $claves[] = new Clave("B8", "NOMBRE ENTID EMISORA",         70, "A", 314);
    $claves[] = new Clave("C1", "TIPO DE SOPORTE",               2, "A", 384);
    $claves[] = new Clave("C2", "RECEPTOR DEL SOPORTE",         65, "A", 386);
    $claves[] = new Clave("C3", "DOMICILIO",                    40, "A", 451);
    $claves[] = new Clave("C4", "LOCALIDAD",                    40, "A", 491);
    $claves[] = new Clave("C5", "CODIGO POSTAL",                 7, "N", 531);
    $claves[] = new Clave("￼￼￼￼￼￼￼￼￼￼￼￼￼￼￼￼C6", "PROVINCIA",                    40, "A", 538);
    $claves[] = new Clave("D1", "NOMBRE ARCHIVO PC",            12, "A", 578);
    $claves[] = new Clave("D2", "TIPO DE MONEDA",                5, "A", 590);
    $claves[] = new Clave("D3", "TIPO DE CAMBIO",                7, "N", 595);
    $claves[] = new Clave("D4", "FECHA VERSION",                 8, "N", 602);
    $claves[] = new Clave("D5", "VERSION DE 977",                5, "A", 610);
    $claves[] = new Clave("E1", "1aLINEA DE ETIQUETA",          56, "A", 615);
    $claves[] = new Clave("E2", "2aLINEA DE ETIQUETA",          30, "A", 671);
    $claves[] = new Clave("E3", "IDENTIFICADOR UNICO",           8, "A", 701);
    $claves[] = new Clave("E4", "NUM REGISTROS TOTALES",         8, "N", 709);
    $claves[] = new Clave("E5", "TIPO DE IMPUESTO",              1, "N", 717);
    $claves[] = new Clave("E6", "LONGITUD ARCHIVO",             10, "N", 718);
    
    return $claves;
}

/**
 * 
 * Guarda el fichero descomprimido.
 * 
 * @param, $resource, el elemento a guardar
 * @param, $completeName, nombre completo (con todo el PATH) del fichero
 * 
 * @return true or false
 * 
 */
function saveUnzippedFile($resource, $completeName){
    if(touch($completeName)){
        $ficheroDescomprimido = fopen($completeName, 'w+');
        $numBytes = fwrite($ficheroDescomprimido, zip_entry_read($resource, zip_entry_filesize($resource)));
        fclose($ficheroDescomprimido); 
        if( $numBytes != 1){
            echo "Se han escrito ".$numBytes." bytes!".PHP_EOL;
            return true;
        }else{
            return false;
        }
        
    }else{
        return false;
    }
    
}


function getEstructura($lineas, $clavesA, $claves00, $claves901000, $claves903000){
    
    $estructuras = array();
       // Recorre nuestro array de líneas
    foreach ($lineas as $num_línea => $linea) {
        // Los 6 primeros caracteres son el código del registro
        $codigo = substr($linea, 0, 6);
        
        // Tratamos los registros que contienen información relevante...
        
        // 901010, tabla de códigos internos
        if($codigo == "901000"){
            procesaLinea901000($linea, $clavesA, $claves901000);
        
        // 000000, info administrativa    
        }else if($codigo == "000000"){
            procesaLinea000000($linea, $clavesA, $claves00);
        
        // 903000, estructura del resto de registros
        }else if($codigo == "903000"){
            $campos = procesaLinea903000($linea, $clavesA, $claves903000);
            $estructuras[$campos[0]->codigoRegistro] = $campos;
        }
        
    } //foreach
    
    return $estructuras;
    
}


function procesaFichero977R($lineas, $estructuras){
    // Ahora tratamos del fichero... los registros que no hemos tratado antes...
    foreach ($lineas as $num_línea => $linea) {
        $codigo = substr($linea, 0, 6);
        
        if($codigo == "903000"){
        }else if($codigo == "000000"){
        }else if($codigo == "901000"){
        }else if(strlen($codigo) == 6){ //evitamos la última línea del fichero...
            procesaRegistro($linea, $estructuras[$codigo]);
        }
    }
    
}

?>