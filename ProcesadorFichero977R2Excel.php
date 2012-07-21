<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('memory_limit', '256M');

/** Include path **/
ini_set('include_path', ini_get('include_path').':/usr/local/pear/share/pear/PHPExcel/');

/** PHPExcel */
require_once('PHPExcel.php');

/** PHPExcel_Writer_Excel2007 */
//include_once('/usr/local/pear/share/pear/PHPExcel/PHPExcel/Writer/Excel2007.php');
//include_once('/usr/local/pear/share/pear/PHPExcel/PHPExcel/Writer/Excel5.php');
require_once('PHPExcel/Writer/Excel2007.php');
require_once('PHPExcel/Writer/Excel5.php');

require_once("Clave.php");
require_once('Conversion.php');
require_once('ProcesadorFichero977R.php');



class ProcesadorFichero977R2Excel extends ProcesadorFichero977R{
    
    private static $DEBUG = FALSE;
    
    public function saveToExcel(){
        
        $index = 1;
        // Create new PHPExcel object
        echo " Create new PHPExcel object".PHP_EOL;
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        echo " Set properties".PHP_EOL;
        $objPHPExcel->getProperties()->setCreator("jherranzm");
        $objPHPExcel->getProperties()->setLastModifiedBy("jherranzm");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
        $objPHPExcel->getProperties()->setDescription("Document for Office 2007 XLSX, generated using PHP classes.");
        
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        
        $objPHPExcel->createSheet($index);
        $objPHPExcel->setActiveSheetIndex($index);
        
        $sheetNames = $objPHPExcel->getSheetNames();
        
        foreach($sheetNames as $sheetName){
            echo "Sheet Name:".$sheetName."".PHP_EOL;
        }
        
        
        // $objPHPExcel->getActiveSheet()->fromArray($this->registros, null, 'A1');
        
        // recorremos la matriz de registros
        // cada registro va a su hoja
        // hay que crear la hoja si no existe.
        
        $columnasAEliminar = array();
        $columnasAEliminar[] = "NOMBRE_ARCHIVO_PC";
        $columnasAEliminar[] = "SECUENCIAL";
        $columnasAEliminar[] = "TABLA_DETALLES";
        $columnasAEliminar[] = "LONGITUD_REGISTRO";
        
        $columnasSinTipo = array();
        $columnasSinTipo[] = "NOMBRE_ARCHIVO_PC";
        $columnasSinTipo[] = "FECHA_DE_EMISION";
        
        // Controlamos las filas de cada pestaña
        $rowNumbers = array();
        try{
            foreach ($this->registros as $key => $registro) {
                
                foreach($columnasAEliminar as $j => $columna){
                    unset($registro[$columna]);
                }
                
               $codigoRegistro = $registro["CODIGO_REGISTRO"];
                // Campos es un array de Objetos Campo
                $campos = $this->estructuras[$codigoRegistro];
                if(self::$DEBUG) echo $campos["CODIGO_REGISTRO"]->toString();
                
                // Si no existe la pestaña, la creamos...
                if( ($sheet = $objPHPExcel->getSheetByName("R".$codigoRegistro)) == FALSE ){
                    if(self::$DEBUG) echo "".$codigoRegistro." No existe!".PHP_EOL;
                    $index = $objPHPExcel->getSheetCount();
                    if(self::$DEBUG) echo "INDEX:".$index."".PHP_EOL;
                     $objPHPExcel->createSheet($index);
                    if(self::$DEBUG) echo "INDEX:".$index."".PHP_EOL;
                    $objPHPExcel->setActiveSheetIndex($index);
                    if(self::$DEBUG) echo "CODIGO_REGISTRO:".$codigoRegistro."".PHP_EOL;
                    $objPHPExcel->getActiveSheet()->setTitle("R".$codigoRegistro);
                    
                    
                    // Cabeceras...
                    $rowNumbers[$codigoRegistro] = 1;
                    $objPHPExcel->getActiveSheet()->fromArray(array_keys($registro), NULL, 'A'.$rowNumbers[$codigoRegistro]);
                    $rowNumbers[$codigoRegistro]++;
                }
                $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($objPHPExcel->getSheetByName("R".$codigoRegistro)));
                // $objPHPExcel->getActiveSheet()->fromArray($registro, NULL, 'A'.$rowNumbers[$codigoRegistro]);
                
                
                $column = 0;
                foreach ($registro as $key => $value) {
                    
                    // Hay dos campos que no aparecen NOMBRE_ARCHIVO_PC, FECHA_DE_EMISION
                    
                    if(!in_array($key, $columnasSinTipo)){
                        $campo = $campos[$key];    
                        if(self::$DEBUG) echo "Tipo del Campo ".$campo->descripcion." ".$campo->tipo.PHP_EOL;
                    } 
                    // // $objPHPExcel->getActiveSheet()->fromArray($registro, NULL, 'A'.$rowNumbers[$codigoRegistro]);
                    
                    
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $rowNumbers[$codigoRegistro], $value);
                    $column++;
                }
                $rowNumbers[$codigoRegistro]++;
            }
            
         }catch (Exception $e){
            echo 'ERROR: ' . $e->getMessage().PHP_EOL;
        }
        
        
        
        try{
            // Save Excel 2007 file
            echo " Write to Excel2007 format".PHP_EOL;
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            echo " Write to Excel2007 format\n".$objPHPExcel->getActiveSheet()->getTitle().PHP_EOL;
            //$str = str_replace('.php', '.xlsx', __FILE__);
            $ruta = "/";
            $file = "excel";        
            date_default_timezone_set('Europe/Berlin');
            $current_time = urlEncode(date("Ymd")."-".date("His"));
            $file .= "-".$current_time;

            $str = dirname(__FILE__).$ruta.$file.".xlsx";
            echo " Write to Excel2007 format\n".$str.PHP_EOL;
            
            $objWriter->save($str);
            echo "File:".$str.PHP_EOL;
            $str = basename($str);
        }catch (Exception $e){
            echo 'ERROR: ' . $e->getMessage().PHP_EOL;
        }
    }


}
?>