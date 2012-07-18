<?php
/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').':/usr/local/pear/share/pear/PHPExcel/');

/** PHPExcel */
require_once('PHPExcel.php');

/** PHPExcel_Writer_Excel2007 */
//include_once('/usr/local/pear/share/pear/PHPExcel/PHPExcel/Writer/Excel2007.php');
//include_once('/usr/local/pear/share/pear/PHPExcel/PHPExcel/Writer/Excel5.php');
require_once('PHPExcel/Writer/Excel2007.php');
require_once('PHPExcel/Writer/Excel5.php');

require_once('utf8.utils.php');
require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');
require_once('Pestanya.php');
require_once('PestanyaService.php');
require_once('InformeXLS.php');
require_once('InformeXLSService.php');
require_once('Prueba.php');
require_once('PruebaService.php');

header('Content-Type: text/html; charset=utf-8');


$br = "<br/>";

echo "include_path: [".ini_get('include_path')."]\n".$br;

// Create new PHPExcel object
echo " Create new PHPExcel object\n".$br;
$objPHPExcel = new PHPExcel();

// Set properties
echo " Set properties\n".$br;
$objPHPExcel->getProperties()->setCreator("jherranzm");
$objPHPExcel->getProperties()->setLastModifiedBy("jherranzm");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
$objPHPExcel->getProperties()->setDescription("Document for Office 2007 XLSX, generated using PHP classes.");

$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);


// Add some data
echo " Add some data\n".$br;
$index = 0;
$service = new ConsultaSQLService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "ConsultaSQL");

mongoSave($result, "ConsultasSQL");

$index++;
$service = new PestanyaService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "Pestanya");

mongoSave($result, "Pestanyes");

$index++;
$service = new InformeXLSService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "InformeXLS");

mongoSave($result, "InformesXLS");

$index++;
$service = new PruebaService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "Prueba");


$texto = "Esto será un texto con 400...";
$num1 = rand(1,3999);
$num2 = 100*(1/rand(0,1971));
$num = $service->addNewTest($texto, $num1, $num2);
echo $num."\n".$br;

$prueba = $service->findById($num);

echo "".json_encode($prueba)."\n".$br;




$ruta = "/downloads/";
$file = "excel";		
$current_time = urlEncode(date("Ymd")."-".date("His"));
$file .= "-".$current_time;
		
try{
	// Save Excel 2007 file
	echo " Write to Excel2007 format\n".$br;
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	echo " Write to Excel2007 format\n".$objPHPExcel->getActiveSheet()->getTitle()."\n".$br;
	//$str = str_replace('.php', '.xlsx', __FILE__);
	$str = dirname(__FILE__).$ruta.$file.".xlsx";
	echo " Write to Excel2007 format\n".$str."\n".$br;
	$objWriter->save($str);
	echo "File:".$str."\n".$br;
	$str = basename($str);
	echo "<a href='".$ruta.$str."'>".$str."</a>".$br;	
}catch (Exception $e){
	echo 'ERROR: ' . $e->getMessage().$br;
}

echo "".json_encode($prueba)."\n".$br;

try{

	// Save Excel 2007 file
	echo " Write to Excel2003 format\n".$br;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	//$str = str_replace('.php', '.xls', __FILE__);
	$str = dirname(__FILE__).$ruta.$file.".xls";
	$objWriter->save($str);
	echo "File:".$ruta.$str."\n".$br;
	$str = basename($str);
	echo "<a href='".$ruta.$str."'>".$str."</a>".$br;
}catch (Exception $e){
	echo 'ERROR: ' . $e->getMessage().$br;
}

echo "".json_encode($prueba)."\n".$br;

try{
    // Connect:
    echo "Connect:"."\n".$br;
    $connection = new Mongo("localhost");
    // Select database:
    echo "DB:"."\n".$br;
    $db = $connection->objetos;
    
    $collection = $db->selectCollection("Pruebas");
    
    echo "Insert:"."\n".$br;
    echo "".json_encode($prueba)."\n".$br;
    $collection->insert(json_decode(json_encode($prueba)));
    //echo "".$prueba."\n".$br;
    
    echo "Recuperamos:"."\n".$br;
    $retrieved = $collection->find();
    echo count($retrieved)."\n".$br;
        foreach ($retrieved as $obj) {
          print_r($obj)."\n".$br;
            echo ""."\n".$br;
        }
} catch(Exception $e){
    echo 'ERROR: ' . $e->getMessage().$br;
}

// Echo done
echo date('H:i:s') . " Done writing file.\r\n".$br;






function rellenaPestanya( 
    $libroExcel, 
    $index, 
    $listaObjetos, 
    $nombrePestanya){
	
			$br = "<br/>";
            $ultimaColumna = -1;
	
			$libroExcel->createSheet($index);
			$libroExcel->setActiveSheetIndex($index);

			error_log("RESULTADO: ".count($listaObjetos));
		
			if($listaObjetos == null) return $libroExcel;
            
			$col = 0;
			$fila = 1;
			$pintarCabecera = TRUE;
			
			foreach($listaObjetos as $elObjeto){
					
				
				$reflect = new ReflectionClass($elObjeto);
				$props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
				
				$worksheet = $libroExcel->getActiveSheet();
				if($pintarCabecera){
					foreach ($props as $prop) {
						$worksheet->setCellValueByColumnAndRow(
							$col++, 
							$fila, 
							utf8_encode($prop->getName())
							);
						//echo " :".$prop->getName().":".gettype($reflect->getProperty($prop->getName()))."\n".$br;
						
					}//foreach ($props as $prop) {
					$pintarCabecera = FALSE;
					
					$highestColumn = $worksheet->getHighestColumn();
                    $ultimaColumna = $col -1;
					$range = 'A1:'.PHPExcel_Cell::stringFromColumnIndex($ultimaColumna).'1';
					
					$style_header = array(                  
            			'fill' => array(
                			'type' => PHPExcel_Style_Fill::FILL_SOLID,
                			'color' => array('rgb'=>'DDDDDD'),
           					 ),
            			'font' => array(
               				 'bold' => true,
            			)
            		);
					
					$worksheet->getStyle($range)->applyFromArray( $style_header );
					
					$fila++;
					$col = 0;
				}//if(!$pintarCabecera){
						
				foreach ($props as $prop) {
					
					$worksheet->setCellValueByColumnAndRow(
						$col++, 
						$fila, 
						utf8_encode($reflect->getProperty($prop->getName())->getValue($elObjeto))
						);
				}//foreach ($props as $prop) {
						
				$fila++;
				$col = 0;
				
					
					
			}//foreach($listaObjetos as $elObjeto){
			// echo $fila."\n".$br;
            // echo "RESULTADO: ".count($listaObjetos)."\n".$br;
            $rango = 'A1:'.PHPExcel_Cell::stringFromColumnIndex($ultimaColumna).(count($listaObjetos)+1);
            echo "RANGO: ".$rango."\n".$br;
            // $libroExcel->getActiveSheet()->setAutoFilter($rango );
            $libroExcel->getActiveSheet()->setAutoFilter( 'A1:' . $libroExcel->getActiveSheet()->getHighestColumn() . $libroExcel->getActiveSheet()->getHighestRow());
            
            echo "".'A1:' . $libroExcel->getActiveSheet()->getHighestColumn() . $libroExcel->getActiveSheet()->getHighestRow()."\n"."<br/>";

            $libroExcel->addNamedRange( new PHPExcel_NamedRange($nombrePestanya, $libroExcel->getActiveSheet(), $rango) );

			// Rename sheet
			echo " Renombramos la pestaña:".$nombrePestanya."\n".$br;
			$libroExcel->getActiveSheet()->setTitle($nombrePestanya);
			
		return $libroExcel;
}


function mongoSave($listaObjetos, $collectionName){
               
    $br = "<br/>";
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
                    
                $_id = $elObjeto->id;
                $_obj = $collection->findOne( array ( "id" => $_id));
                if(!$_obj){ //existe..
                    echo "Insert:".json_encode($elObjeto)."\n".$br;
                    $collection->insert(json_decode(json_encode($elObjeto)));
                } //if
            } // foreach
        
        echo "Recuperamos:"."\n".$br;
        $retrieved = $collection->find();
        echo count($retrieved)."\n".$br;
        // foreach ($retrieved as $obj) {
              // print_r($obj)."\n".$br;
              // echo ""."\n".$br;
        // } // foreach
        
    } catch(Exception $e){
        echo 'ERROR: ' . $e->getMessage().$br;
    } // try
}

?>