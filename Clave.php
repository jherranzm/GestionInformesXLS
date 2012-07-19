<?php


class Clave{
    
    public $zona;
    public $campo;
    public $longitud;
    public $formato;
    public $posicion;
    public $observaciones;
    
    /**
     * 
     * @param $z, Zona de la lista de claves
     * @param $c, Nombre del campo
     * @param $l, longitud del campo
     * @param $f, formato del campo
     * @param $p, posición del campo
     * 
     */
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


class Clave901010{
    public $numeroVeces;
    public $numeroPosiciones;
    public $codigoExterno;
    public $codigoInterno;
    public $codigoEquipoNoRenovado;
    public $descripcion;
    
    function __construct($nv, $np, $ce, $ci, $cenr){
        $this->numeroVeces = $nv;
        $this->numeroPosiciones = $np;
        $this->codigoExterno = $ce;
        $this->codigoInterno = $ci;
        $this->codigoEquipoNoRenovado = $cenr;
    }
    
    public function toString(){
        echo  "".$this->numeroVeces.", ".$this->numeroPosiciones.", ".$this->codigoExterno.", ".$this->codigoInterno.", ".$this->codigoEquipoNoRenovado;
                
    }
    
}

class Campo{
    
    public $codigoRegistro;
    public $numeroBloque;
    public $numeroBloquePadre;
    
    public $descripcion;
    public $tipo;
    public $formato;
    public $posicion;
    public $longitud;
    public $repetido;
    public $tablaAuxiliar;
    
    function __construct($cr, $nb, $nbp, $d, $t, $f, $p, $l, $r, $ta){
    	$this->codigoRegistro      = $cr;
    	$this->numeroBloque        = $nb;
    	$this->numeroBloquePadre   = $nbp;
            
    	$this->descripcion         = $d;
    	$this->tipo                = $t;
    	$this->formato             = $f;
    	$this->posicion            = $p;
    	$this->longitud            = $l;
    	$this->repetido            = $r;
    	$this->tablaAuxiliar       = $ta;
    }
    
    public function toString(){
        echo  
            ""
            .$this->codigoRegistro.", "
            .$this->numeroBloque.", "
            .$this->numeroBloquePadre.", "
            .$this->descripcion.", "
            .$this->tipo.", ".$this->formato.", "
            .$this->posicion.", ".$this->longitud
            .$this->repetido.", ".$this->tablaAuxiliar
            ;
                
    }
    
}

class Estructura{
    public $codigoRegistro;
    public $campos = array();
    
    function __construct($cr, $_campos){
        $this->codigoRegistro      = $cr;
        $this->campos              = $_campos;
    }
}

?>