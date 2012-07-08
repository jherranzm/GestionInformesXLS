<?php
/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').':/usr/local/pear/share/pear/PHPExcel/');

/** PHPExcel */
include_once('PHPExcel.php');

/** PHPExcel_Writer_Excel2007 */
//include_once('/usr/local/pear/share/pear/PHPExcel/PHPExcel/Writer/Excel2007.php');
//include_once('/usr/local/pear/share/pear/PHPExcel/PHPExcel/Writer/Excel5.php');
include_once('PHPExcel/Writer/Excel2007.php');
include_once('PHPExcel/Writer/Excel5.php');

$br = "<br/>";

echo "include_path: [".ini_get('include_path')."]\n".$br;

// Create new PHPExcel object
echo " Create new PHPExcel object\n".$br;
$objPHPExcel = new PHPExcel();

// Set properties
echo " Set properties\n".$br;
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");


// Add some data
echo " Add some data\n".$br;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');
$objPHPExcel->getActiveSheet()->SetCellValue('A3', date("Y-m-d"));
$objPHPExcel->getActiveSheet()->SetCellValue('A4', date("H:i:s"));

$fila = 0;
while($fila < 10){
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5 + $fila, date("H:i:s"));
	$fila++;
}

// Rename sheet
echo " Rename sheet\n".$br;
$objPHPExcel->getActiveSheet()->setTitle('Simple');

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
?>