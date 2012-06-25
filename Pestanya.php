<?php
class Pestanya{

	public $id;
	public $nombre;
	public $rango;	    
	public $numfilainicial;
	public $consultaid;
	public $nombreConsulta;
	

	public function toString(){
		return "Id: [".$this->id."],  "
				."Nombre: [".$this->nombre."], "
				."Rango: [".$this->rango."], "
				."NumFilaInicial: [".$this->numfilainicial."], "
				."ConsultaId: [".$this->consultaid."] "
				."\n"."<br/>";
	}
}
?>