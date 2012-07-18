<?php


class Clave{
    
    public $zona;
    public $campo;
    public $longitud;
    public $formato;
    public $posicion;
    public $observaciones;
    
    function __construct($z, $c, $l, $f, $p){
        $this->zona = $z;
        $this->campo = $c;
        $this->longitud = $l;
        $this->formato = $f;
        $this->posicion = $p;
    }
    
    public function toString(){
        echo  "".$this->zona.", ".$this->campo.", ".$this->longitud.", ".$this->formato.", ".$this->posicion;
                
    }
    
}



foreach ($clavesA as $key => $value) {
	echo "".$value->toString()."\n";
}
?>