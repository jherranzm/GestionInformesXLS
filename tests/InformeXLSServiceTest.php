<?php
    
require_once('ConsultaSQLService.php');
require_once('InformeXLSService.php');

class InformeXLSServiceTest  extends PHPUnit_Framework_TestCase{
    
    private $id = -1;
    
    public function testInformeXLSGetById(){
        $id = 1;
        $svc = new InformeXLSService();
        $inf = $svc->getById($id);   
        echo "testInformeXLSGetById:".$inf->toString().""."\n";
        $this->assertEquals("AcuerdoAplicado", $inf->nombre);
    }
    
    public function testInformeXLSSave(){
        $_nombre = "NuevaInforme".rand(1,234);
        
        $nuevoinforme = new InformeXLS();
        $this->assertEquals("", $nuevoinforme->nombre);
        
        $nuevoinforme->nombre = $_nombre;
        
        $svc = new InformeXLSService();
        $id = $svc->save($nuevoinforme);   
        echo "testInformeXLSSave:".$id.""."\n";
        $this->assertGreaterThan(0, $id);
        
        $inf = $svc->getById($id);   
        $this->assertEquals($_nombre, $inf->nombre);
        echo "testInformeXLSGetById:".$inf->toString().""."\n";
    }
    
    
}


        
        
?>