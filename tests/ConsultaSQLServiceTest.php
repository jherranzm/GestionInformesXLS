<?php
    
require_once('ConsultaSQLService.php');

class ConsultaSQLServiceTest  extends PHPUnit_Framework_TestCase{
    
    public function testConsultaSQLGetById(){
        $idConsulta = 1;
        $svc = new ConsultaSQLService();
        $obj = $svc->getById($idConsulta);   
        $this->assertEquals("977R.getAcuerdos", $obj->nombre);
    }


    public function testConsultaSQLSave(){
        $_nombre = "NuevaConsulta".rand(1,234);
        $obj = new ConsultaSQL();
        $this->assertEquals("", $obj->nombre); // Nombre vacio por defecto
        $obj->nombre = $_nombre;
        
        $svc = new ConsultaSQLService();
        $_id = $svc->save($obj);   
        echo "".$_id.""."\n";
        $this->assertGreaterThan(0, $_id);

        $obj = $svc->getById($_id);   
        $this->assertEquals($_nombre, $obj->nombre);
    }
    
}


        
        
?>