<?php
/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').':/usr/local/pear/share/pear/PHPExcel/');

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'Writer/Excel2007.php';
include 'Writer/Excel5.php';

// Create new PHPExcel object
echo " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
echo " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");


// Add some data
echo " Add some data\n";
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

// Rename sheet
echo " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file
echo " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$str = str_replace('.php', '.xlsx', __FILE__);
$objWriter->save($str);
echo $str."\n";


// Save Excel 2007 file
echo " Write to Excel2003 format\n";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$str = str_replace('.php', '.xls', __FILE__);
$objWriter->save($str);
echo $str."\n";
// Echo done
echo date('H:i:s') . " Done writing file.\r\n";
?>