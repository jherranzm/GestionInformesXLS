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


// Add some data
echo " Add some data\n".$br;
$index = 0;
$service = new ConsultaSQLService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "ConsultaSQL");

$index++;
$service = new PestanyaService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "Pestanya");

$index++;
$service = new InformeXLSService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "InformeXLS");

$index++;
$service = new PruebaService();
$result = $service->listAll();
$objPHPExcel = rellenaPestanya($objPHPExcel, $index, $result, "Prueba");




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

// Echo done
echo date('H:i:s') . " Done writing file.\r\n".$br;




function rellenaPestanya( $libroExcel, $index, $listaObjetos, $nombrePestanya){
	
			$br = "<br/>";
	
			$libroExcel->createSheet($index);
			$libroExcel->setActiveSheetIndex($index);

			error_log("RESULTADO: ".count($listaObjetos));
		
			if($listaObjetos == null){
		
			}else{
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
						}//foreach ($props as $prop) {
						$pintarCabecera = FALSE;
						
						$highestColumn = $worksheet->getHighestColumn();
						$range = 'A1:'.PHPExcel_Cell::stringFromColumnIndex($col-1).'1';
						
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
		
			}//if($listaObjetos == null){


			// Rename sheet
			echo " Renombramos la pestaña:".$nombrePestanya."\n".$br;
			$libroExcel->getActiveSheet()->setTitle($nombrePestanya);
			
		return $libroExcel;
}
?>