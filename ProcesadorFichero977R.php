<?php
require_once("Clave.php");
require_once('Conversion.php');

define(DIRECTORY_SEPARATOR, '/');

class ProcesadorFichero977R{
    
    private static $DEBUG = FALSE;
    
    private $ficheroZip;
    private $zip;

    private $clavesA;
    private $claves901000;
    private $claves903000;
    private $claves00;
    public $estructuras;
    public $datosAdministrativos;
    public $registros;
    public $tablasAuxiliares;
    public $camposTraducidos;
    
    /**
     * 
     */
    function __construct($f){
        $this->ficheroZip = $f;
        $this->init();
     }
    
    function init(){
        $this->clavesA = $this->loadClaves();
        $this->claves901000 = $this->loadClaves901000();
        $this->claves903000 = $this->loadClaves903000();
        $this->claves00 = $this->loadClaves000000();
        $this->estructuras = array();
        $this->datosAdministrativos = array();
        $this->registros = array();
        $this->tablasAuxiliares = array();
        $this->camposTraducidos = $this->getCamposTraducidos();
    }
    
    
    function execute(){
        
        $zipDir = getcwd() . DIRECTORY_SEPARATOR;
        
        $this->zip = zip_open($this->ficheroZip);
        if(!$this->zip){
            echo "ERROR! No se ha podido abrir el fichero ".$this->ficheroZip.PHP_EOL;
            return false;
        }
            
        // Leemos todos los ficheritos que haya en el Zip, aunque en teoría sólo hay uno...!
        while ($resource = zip_read($this->zip)) {
            echo "Nombre:               " . zip_entry_name($resource) . PHP_EOL;
            echo "Tamaño actual del fichero:    " . zip_entry_filesize($resource) . PHP_EOL;
            echo "Tamaño comprimido:    " . zip_entry_compressedsize($resource) . PHP_EOL;
            echo "Método de compresión: " . zip_entry_compressionmethod($resource) . PHP_EOL;
            $completeName = $zipDir . zip_entry_name($resource);
            echo "Nombre completo del fichero:".$completeName.PHP_EOL;
    
            if (zip_entry_open($this->zip, $resource, "r")) {
                
                $ret = $this->saveUnzippedFile($resource, $completeName);
                
                $lineas = file($completeName);
                
                $this->estructuras = $this->getEstructura($lineas);
                
                print_r($this->camposTraducidos);
                print_r($this->tablasAuxiliares);
                
                $this->procesaFichero977R($lineas);
                
                unset($lineas);
                
                zip_entry_close($resource);
            }
            echo PHP_EOL;
        }
        zip_close($this->zip);
    }
    
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

    function getCamposTraducidos(){
                
        $tablaAuxiliar = array();
        $tablaAuxiliar["CODIGO_CONCEPTO"] = "EPI";            
        $tablaAuxiliar["CONCEPTO_FACTURABLE"] = "TQE";            
        $tablaAuxiliar["AMBITO_DE_TRAFICO"] = "TLL";            
        $tablaAuxiliar["TARIFA"] = "TRF";            
        $tablaAuxiliar["NIVEL_IMPOSITIVO"] = "E-G";         
        
        return $tablaAuxiliar;   
                
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
        if(!touch($completeName)){
             return FALSE;
        }//if
        if( ($ficheroDescomprimido = fopen($completeName, 'w+')) === FALSE){
             return FALSE;
        }
        $numBytes = fwrite($ficheroDescomprimido, zip_entry_read($resource, zip_entry_filesize($resource)));
        fclose($ficheroDescomprimido); 
        
        if($numBytes == 0){
            return FALSE;
        }
        echo "Se han escrito ".$numBytes." bytes!".PHP_EOL;
        return true;
    }
    
    /**
     * 
     * 
     * 
     */
    function getEstructura($lineas){
    
        $estructuras = array();
           // Recorre nuestro array de líneas
        foreach ($lineas as $num_línea => $linea) {
            // Los 6 primeros caracteres son el código del registro
            $codigo = substr($linea, 0, 6);
            
            // Tratamos los registros que contienen información relevante...
            
            // 901010, tabla de códigos internos
            if($codigo == "901000"){
                $this->procesaLinea901000($linea);
            
            // 000000, info administrativa    
            }else if($codigo == "000000"){
                $this->procesaLinea000000($linea);
            
            // 903000, estructura del resto de registros
            }else if($codigo == "903000"){
                $campos = $this->procesaLinea903000($linea);
                $estructuras[$campos["CODIGO_REGISTRO"]->codigoRegistro] = $campos;
            }
            
        } //foreach
        
        return $estructuras;
        
    }
    
    /**
     * 
     * @param $linea, la línea a tratar
     * @param $clavesA, la lista de campos en la zonaA (comunes)
     * @param $claves00, las claves específicas del registro 00.00.00
     */
    function procesaLinea000000($linea){
        
        $str = "";
    
        foreach ($this->clavesA as $key => $value) {
            $_txt = trim(substr($linea, $value->posicion - 1, $value->longitud));
            $str .= $_txt.";";
            $_k = str_replace(" ", "_", trim($value->campo));
            $this->datosAdministrativos[$_k] = $_txt;
        }
        
        foreach ($this->claves00 as $key => $value) {
            $_txt = trim(substr($linea, $value->posicion - 1, $value->longitud));
            $str .= $_txt.";";
            $_k = str_replace(" ", "_", trim($value->campo));
            $this->datosAdministrativos[$_k] = $_txt;
        }
        // echo $str.PHP_EOL;
        $str = ""
            .$this->datosAdministrativos["NOMBRE_ARCHIVO_PC"].";"
            .$this->datosAdministrativos["FECHA_DE_EMISION"].";"
            .$str;
        echo $str.PHP_EOL;
        
    }
    
    function procesaLinea901000($linea){
    
        $str = "";
        $longitudRegistro = 0;
    
        foreach ($this->clavesA as $key => $value) {
            //echo "".$value->longitud."\t".$value->posicion.PHP_EOL;
            $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
            $longitudRegistro = (int) substr($linea, $value->posicion - 1, $value->longitud);
        }
        if (self::$DEBUG) echo $str.PHP_EOL;
        
        $str = "";
        $pos = 0;
        if (self::$DEBUG) echo "Longitud Registro: ".$longitudRegistro.PHP_EOL;
        $repeticiones = (int) substr($linea, $this->claves901000[0]->posicion - 1, $this->claves901000[0]->longitud);
        if (self::$DEBUG) echo "Repeticiones: ".$repeticiones.PHP_EOL;
        $numPos = (int) substr($linea, $this->claves901000[1]->posicion - 1, $this->claves901000[1]->longitud);
        if (self::$DEBUG) echo "Numero Posiciones: ".$numPos.PHP_EOL;
        
        $this->claves901000[5]->longitud = $numPos;
        
        $pos = $this->claves901000[1]->posicion + $this->claves901000[1]->longitud - 1;
        if (self::$DEBUG) echo "Posición: ".$pos.PHP_EOL;
        
        for($index = 0; $index < $repeticiones; $index++){
            if (self::$DEBUG) echo "".PHP_EOL;
            $_tabla = "";
            $_clave = "";
            $_reg901010 = array();
            for ($k = 2; $k < 6 ; $k++) {
                $_long = $this->claves901000[$k]->longitud;
                $posF = $pos + $_long;
                if($longitudRegistro < $posF){ // Nos pasamos...
                    $_long = $longitudRegistro - $pos;
                }
                $_txt = utf8_encode(trim(substr($linea, $pos, $_long)));
                $str .= $_txt.";";
                $pos += $_long;
                if (self::$DEBUG) echo $this->claves901000[$k]->campo."\t#".$_txt."#".PHP_EOL;
                if($k == 2) $_tabla = $_txt;
                if($k == 3) $_clave = $_txt;
                $_reg901010[$this->claves901000[$k]->campo] = $_txt;
            }
            $_clave2 = $_tabla.";".$_clave;
            $this->tablasAuxiliares[$_clave2] = $_reg901010;
        }
        
        echo "".$str.PHP_EOL;
        
        
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
    function procesaLinea903000($linea){
        
        $campos = array();
        
        $str = "";
        $longitudRegistro = 0;
    
        foreach ($this->clavesA as $key => $value) {
            //echo "".$value->longitud."\t".$value->posicion.PHP_EOL;
            $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
            $longitudRegistro = (int) substr($linea, $value->posicion - 1, $value->longitud);
        }
        if (self::$DEBUG) echo $str.PHP_EOL;
        
        $str = "";
        $pos = 0;
        
        if (self::$DEBUG) echo "Longitud Registro: ".$longitudRegistro.PHP_EOL;
        
        $codigoRegistro = substr($linea, $this->claves903000[0]->posicion - 1, $this->claves903000[0]->longitud);
        if (self::$DEBUG) echo "Codigo Registro: ".$codigoRegistro.PHP_EOL;
        
        $numeroBloques = (int) substr($linea, $this->claves903000[1]->posicion - 1, $this->claves903000[1]->longitud);
        if (self::$DEBUG) echo "Número de Bloques: ".$numeroBloques.PHP_EOL;
        
        $numeroCampos = (int) substr($linea, $this->claves903000[2]->posicion - 1, $this->claves903000[2]->longitud);
        if (self::$DEBUG) echo "Número de Campos: ".$numeroCampos.PHP_EOL;
        
        $pos = $this->claves903000[2]->posicion + $this->claves903000[2]->longitud - 1;
        
        for($kb = 0; $kb < $numeroBloques; $kb++){
            
            $numeroBloque = (int) substr($linea, $pos, $this->claves903000[3]->longitud);
            $pos += (int) $this->claves903000[3]->longitud;
            
            $numeroBloquePadre = (int) substr($linea, $pos, $this->claves903000[4]->longitud);
            $pos += (int) $this->claves903000[4]->longitud;
            
            if (self::$DEBUG) echo PHP_EOL.PHP_EOL;
            if (self::$DEBUG) echo "Numero de Bloque:".$numeroBloque.PHP_EOL;
            if (self::$DEBUG) echo "Numero de Bloque Padre:".$numeroBloquePadre.PHP_EOL;
            
            for($kc = 0; $kc < $numeroCampos; $kc++){
                    
                if (self::$DEBUG) echo PHP_EOL.PHP_EOL;
                $d = ""; $t = ""; $f = ""; $p = 0; $l = 0; $r = 0; $ta = "";
                for($i = 5; $i < 12; $i++){
                    $_txt = substr($linea, $pos, $this->claves903000[$i]->longitud);
                    $pos += (int) $this->claves903000[$i]->longitud;
                    
                    if($this->claves903000[$i]->formato == "N"){
                        if (self::$DEBUG) echo $pos.":".$longitudRegistro."\t\t".$this->claves903000[$i]->campo." : ".(int)$_txt.PHP_EOL;
                    }else{
                        if (self::$DEBUG) echo $pos.":".$longitudRegistro."\t\t".$this->claves903000[$i]->campo." : ".trim($_txt).PHP_EOL;
                    } //if
                    
                    switch ($i) {
                        case 5: $d = str_replace(" ", "_", trim($_txt)); break;
                        case 6: $t = trim($_txt); break;
                        case 7: $f = trim($_txt); break;
                        case 8: $p = (int) $_txt; break;
                        case 9: $l = (int) $_txt; break;
                        case 10: $r = (int) $_txt; break;
                        case 11: $ta = trim($_txt); break;
                        
                        default: break;
                    } //switch
                }//for
                
                if($d == ""){
                }else if($d == "OCURRENCIAS" && $l == 0){
                }else{
                    $campos[$d] = new Campo($codigoRegistro, $numeroBloque, $numeroBloquePadre, $d, $t, $f, $p, $l, $r, $ta);
                }
                
            }//for
        }//for
        
        //print_r($campos);
        
        return $campos;
        
        
    }


    function procesaFichero977R($lineas){
        // Ahora tratamos del fichero... los registros que no hemos tratado antes...
        foreach ($lineas as $num_línea => $linea) {
            $codigo = substr($linea, 0, 6);
            
            if($codigo == "903000"){
            }else if($codigo == "000000"){
            }else if($codigo == "901000"){
            }else if(strlen($codigo) == 6){ //evitamos la última línea del fichero...
                $this->procesaRegistro($linea, $this->estructuras[$codigo]);
            }
        }
        
    }
    
    
    function procesaRegistro($linea, $campos){
        $pos = 0; // donde estamos...
        $repeticionesBloque2 = 0;
        $longitudRegistro = 0;
        
        $registro = array();
        
        // Recorremos el primer bloque. Este NO se repite
        $strBloque1 = ""
            .$this->datosAdministrativos["NOMBRE_ARCHIVO_PC"].";"
            .$this->datosAdministrativos["FECHA_DE_EMISION"].";";
        $registro["NOMBRE_ARCHIVO_PC"]  =  $this->datosAdministrativos["NOMBRE_ARCHIVO_PC"];
        $registro["FECHA_DE_EMISION"]  =  $this->datosAdministrativos["FECHA_DE_EMISION"];
        
         
        foreach ($campos as $key => $campo) {
            if($campo->numeroBloque == 1){
                $_txt = substr($linea, $pos, $campo->longitud);
                $pos += $campo->longitud;
                
                if($campo->descripcion == "OCURRENCIAS"){
                    $repeticionesBloque2 = (int) $_txt;
                 }else if($campo->descripcion == "LONGITUD_REGISTRO"){
                    $longitudRegistro = (int) $_txt;
                 }
                 
                 if( strpos($campo->descripcion, "DURACION") > -1){
                     $_txt = ConversorNumerico::conversorAMinutos($_txt, $campo->longitud);
                     // $registro[$campo->descripcion]  =  rtrim($_txt);
                     $registro[$campo->descripcion]  =  (float)$_txt;
                 }else if( strpos($campo->descripcion, "HORA") > -1){
                     $_txt = ConversorNumerico::conversorAHHMMSS($_txt);
                     $registro[$campo->descripcion]  =  rtrim($_txt);
                 }else if($campo->tipo == "I" || $campo->tipo == "N"){ // Numérico
                     $_txt = ConversorNumerico::conversion($_txt, $campo->formato);
                     $_es_float = strpos($campo->formato, ",");
                     if($_es_float > 0){
                         $registro[$campo->descripcion]  =  (float)$_txt;
                     }else{
                         $registro[$campo->descripcion]  =  (int)$_txt;
                     }
                     
                 }else{
                     $registro[$campo->descripcion]  =  rtrim($_txt);
                 }
                 
                 // if($campo->tablaAuxiliar == ""){
//                         
                 // }else{
                    // if(array_key_exists($campo->tablaAuxiliar.";".$registro[$campo->descripcion], $this->tablasAuxiliares)){
                        // echo "".$campo->descripcion.":".$campo->tablaAuxiliar.":"
                                // .$registro[$campo->descripcion].":"
                                // .$this->tablasAuxiliares[$campo->tablaAuxiliar.";".$registro[$campo->descripcion]]["DESCRIPCION"]."!".PHP_EOL;
                        // $registro["DESC_".$campo->descripcion] = $this->tablasAuxiliares[$campo->tablaAuxiliar.";".$registro[$campo->descripcion]]["DESCRIPCION"];
                    // }else{
                        // $registro["DESC_".$campo->descripcion] = "";
                    // }
                 // }
                 
                if(key_exists($campo->descripcion, $this->camposTraducidos)){
                    $registro["DESC_".$campo->descripcion] = $this->getInfoFromTablaAuxiliar($this->camposTraducidos[$campo->descripcion], $registro, $campo );
                }
                 
                 
                 $strBloque1 .= $_txt.";";
                 
                 
            }
            
        }
        
        
        /**
         * Tratamos el bloque 2, que se repite...
         */
        for($k = 0; $k < $repeticionesBloque2; $k++){
            // $strBloque2 = "";
             foreach ($campos as $key => $campo) {
                if($campo->numeroBloque == 2){
                    $_txt = substr($linea, $pos, $campo->longitud);
                     if( strpos($campo->descripcion, "DURACION") > -1){
                        $_txt = ConversorNumerico::conversorAMinutos($_txt, $campo->longitud);
                         // $registro[$campo->descripcion]  =  rtrim($_txt);
                         $registro[$campo->descripcion]  =  (float)$_txt;
                     }else if( strpos($campo->descripcion, "HORA") > -1){
                        $_txt = ConversorNumerico::conversorAHHMMSS($_txt);
                        $registro[$campo->descripcion]  =  rtrim($_txt);
                     }else if($campo->tipo == "I" || $campo->tipo == "N"){ // Numérico
                        $_txt = ConversorNumerico::conversion($_txt, $campo->formato);
                        $_es_float = strpos($campo->formato, ",");
                         if($_es_float > 0){
                             $registro[$campo->descripcion]  =  (float)$_txt;
                         }else{
                             $registro[$campo->descripcion]  =  (int)$_txt;
                         }
                     }else{
                         $registro[$campo->descripcion]  =  rtrim($_txt);
                     } //if
                    // $strBloque2 .= $_txt.";";
                    // if($campo->tablaAuxiliar == ""){
                        // // No hay datos adicionales
                    // }else{
                        // echo "".$campo->descripcion.":".$campo->tablaAuxiliar.":"
                            // .$registro[$campo->descripcion]
                            // .$this->tablasAuxiliares[$campo->tablaAuxiliar.";".$registro[$campo->descripcion]]["DESCRIPCION"]."!".PHP_EOL;
                    // }
                    
                    $pos += $campo->longitud;
                } //if
                
                if(array_key_exists($campo->descripcion, $this->camposTraducidos)){
                    $registro["DESC_".$campo->descripcion] = $this->getInfoFromTablaAuxiliar($this->camposTraducidos[$campo->descripcion], $registro, $campo );
                // }else{
                    // echo "".$campo->descripcion." no tiene tabla auxiliar!".PHP_EOL;
                }
                
                 // if($campo->descripcion == "CONCEPTO_FACTURABLE"){
                    // $registro["DESC_".$campo->descripcion] = $this->getInfoFromTablaAuxiliar("TQE", $registro, $campo );
                 // }else if($campo->descripcion == "AMBITO_DE_TRAFICO"){
                    // $registro["DESC_".$campo->descripcion] = $this->getInfoFromTablaAuxiliar("TLL", $registro, $campo );
                 // }else if($campo->descripcion == "TARIFA"){
                    // $registro["DESC_".$campo->descripcion] = $this->getInfoFromTablaAuxiliar("TRF", $registro, $campo );
                 // }else if($campo->descripcion == "NIVEL_IMPOSITIVO"){
                    // $registro["DESC_".$campo->descripcion] = $this->getInfoFromTablaAuxiliar("E-G", $registro, $campo );
                 // }
             } //foreach
             
             if($pos > $longitudRegistro){
                 $k = $repeticionesBloque2 + 1;
             } //if
             //echo $strBloque1.$strBloque2.PHP_EOL;
             $this->registros[] = $registro;
        }//for($k = 0; $k < $repeticionesBloque2; $k++){
            
                
            
    }// function procesaRegistro($linea, $campos) 
    
    
    function getInfoFromTablaAuxiliar($codInterno, $_registro, $_campo ){
        if(array_key_exists($codInterno.";".$_registro[$_campo->descripcion], $this->tablasAuxiliares)){
            $str = $this->tablasAuxiliares[$codInterno.";".$_registro[$_campo->descripcion]]["DESCRIPCION"];
        }else{
            $str = "";
        }
        
        return $str;
        
    }
}
?>