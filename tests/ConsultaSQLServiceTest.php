<?php
    
require_once('ConsultaSQLService.php');
require_once('InformeXLSService.php');

class ConsultaSQLServiceTest  extends PHPUnit_Framework_TestCase{
    
    public function testConsultaSQLGetById(){
        $idConsulta = 1;
        $svc = new ConsultaSQLService();
        $cons = $svc->getById($idConsulta);   
        $this->assertEquals("977R.getAcuerdos", $cons->nombre);
    }


    public function testInformeXLSGetById(){
        $id = 1;
        $svc = new InformeXLSService();
        $inf = $svc->getById($id);   
        $this->assertEquals("AcuerdoAplicado", $inf->nombre);
    }
    
    public function testInformeXLSSave(){
        $nuevoinforme = new InformeXLS();
        $this->assertEquals("", $nuevoinforme->nombre);
        
        $svc = new InformeXLSService();
        $inf = $svc->save($nuevoinforme);   
        echo $inf.""."\n";
        $this->assertGreaterThan(0, $inf);
    }
    
    public function testSuper(){
        $idInforme = 1;
        $svc = new InformeXLSService();
        $informe = $svc->getById($idInforme);   

        echo $idInforme." : ".$informe->nombre."\n"; 
        
        $informe2 = new InformeXLS();
        $informe2->nombre = "Nuevo Informe";
        $res = $svc->save($informe2);
        
        echo $res."\n"; 
        $informe = $svc->getById($res);
        echo $idInforme." : ".$informe->nombre."\n"; 
        
        $informe->nombre = $informe->nombre."".rand(1,234);

        $f = $svc->update($informe);
        
        assert($f);
        $informe3 = $svc->getById($res);
        echo $idInforme." : ".$informe3->nombre."\n"; 
        
        $res = $svc->delete($res);
        echo $res."\n"; 
        
    }
    
}


        
        
?>