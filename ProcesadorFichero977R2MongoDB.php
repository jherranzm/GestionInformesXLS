<?php
require_once("Clave.php");
require_once('Conversion.php');
require_once('ProcesadorFichero977R.php');



class ProcesadorFichero977R2MongoDB extends ProcesadorFichero977R{
    
    function mongoSave($listaObjetos, $collectionName){
               
        try{
            // Connect:
            // echo "Connect:"."\n".$br;
            $connection = new Mongo("localhost");
            // Select database:
            // echo "DB:"."\n".$br;
            $db = $connection->objetos;
            
            // echo "Collection:"."\n".$br;
            $collection = $db->selectCollection($collectionName);
            
                foreach($listaObjetos as $elObjeto){
                        
                    // $_id = $elObjeto->id;
                    // $_obj = $collection->findOne( array ( "id" => $_id));
                    // if(!$_obj){ //existe..
                        echo "Insert:".json_encode($elObjeto).PHP_EOL;
                        $collection->insert(json_decode(json_encode($elObjeto)));
                    // } //if
                } // foreach
            
            echo "Recuperamos:".PHP_EOL;
            $retrieved = $collection->find();
            echo count($retrieved).PHP_EOL;
            // foreach ($retrieved as $obj) {
                  // print_r($obj)."\n".$br;
                  // echo ""."\n".$br;
            // } // foreach
            
        } catch(Exception $e){
            echo 'ERROR: ' . $e->getMessage().PHP_EOL;
        } // try
    }
}

?>