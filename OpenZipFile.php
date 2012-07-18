<?php

require_once("Clave.php");

define(DIRECTORY_SEPARATOR, '/');

$zipDir = getcwd() . DIRECTORY_SEPARATOR;

echo $zipDir."\n".$crlf.$br;

$br = "<br/>";
$crlf = "\r\n";
$file = "/Users/jherranzm/Downloads/Fichero_Ejemplo.zip";
$zip = zip_open($file);

$clavesA = loadClaves();

if(!$zip){
    echo "ERROR!".$crlf.$br;
}else{
    while ($resource = zip_read($zip)) {
        echo "Nombre:               " . zip_entry_name($resource) . "\n";
        echo "Tamaño actual del fichero:    " . zip_entry_filesize($resource) . "\n";
        echo "Tamaño comprimido:    " . zip_entry_compressedsize($resource) . "\n";
        echo "Método de compresión: " . zip_entry_compressionmethod($resource) . "\n";
        $completeName = $zipDir . zip_entry_name($resource);
        echo $completeName."\n".$crlf.$br;

        if (zip_entry_open($zip, $resource, "r")) {
            echo "Contenido del fichero:\n".$crlf.$br;
            //$buf = zip_entry_read($resource, zip_entry_filesize($resource));
            //echo "$buf\n";
            if(touch($completeName)){
                $ficheroDescomprimido = fopen($completeName, 'w+');
                $numBytes = fwrite($ficheroDescomprimido, zip_entry_read($resource, zip_entry_filesize($resource)));
                if( $numBytes != 1){
                    echo "Se han escrito ".$numBytes.$crlf.$br;
                }
                fclose($ficheroDescomprimido); 
                
            }
            $lineas = file($completeName);

            // Recorre nuestro array, muestra el código fuente HTML como tal
            // y muestra tambíen los números de línea.
            foreach ($lineas as $num_línea => $linea) {
                //echo "Linea #<b>{$num_línea}</b> : " . htmlspecialchars($linea) . "<br />\n";
                procesaLinea($linea, $clavesA);
            }

            zip_entry_close($resource);
        }
        echo "\n";

    }

    zip_close($zip);
}


function procesaLinea($linea, $clavesA){
    
    $str = "";
    foreach ($clavesA as $key => $value) {
        //echo "".$value->longitud."\t".$value->posicion."\n";
        $str .= substr($linea, $value->posicion - 1, $value->longitud).";";
    }
    echo $str."\n".PHP_EOL;
}

function loadClaves(){
    $clavesA = array();


        $claveA1 = new Clave("A1", "CODIGO DE REGISTRO", 6, "N", 1);
        $clavesA[] = $claveA1;
        
        $claveA2 = new Clave("A2", "NUMERO SECUENCIAL", 8, "N", 7);
        $clavesA[] = $claveA2;
        
        $claveA3 = new Clave("A3", "CODIGO CLIENTE", 8, "N", 15);
        $clavesA[] = $claveA3;
        
        $claveA4 = new Clave("A4", "AGRUPACION FACTURABLE", 12, "A", 23);
        $clavesA[] = $claveA4;
        
        $claveA5 = new Clave("A5", "AGRUPACION PARA DETALLE", 12, "A", 35);
        $clavesA[] = $claveA5;
        
        $claveA6 = new Clave("A6", "TIPO SERVICIO", 12, "A", 47);
        $clavesA[] = $claveA6;
        
        $claveA7 = new Clave("A7", "MULTICONEXION", 20, "A", 59);
        $clavesA[] = $claveA7;
        
        $claveA8 = new Clave("A8", "NUMERO COMERCIAL 1", 20, "A", 79);
        $clavesA[] = $claveA8;
        
        $claveA9 = new Clave("A9", "NUMERO COMERCIAL 2", 20, "A", 99);
        $clavesA[] = $claveA9;
        
        $claveA10 = new Clave("A10", "LONGITUD REGISTRO", 4, "N", 119);
        $clavesA[] = $claveA10;
        
        return $clavesA;
}

?>