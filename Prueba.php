<?php
class Prueba{

	public $id;
	public $fecha;
	public $fecha2;
	public $textoLargo;
	public $valorDecimal;
	public $valorFloat;
	
	

	public function toString(){
		return "Id: [".$this->id."],  "
				."Fecha: [".$this->fecha."], "
				."Fecha2: [".$this->fecha2."], "
				."textoLargo: [".$this->textoLargo."], "
				."valorDecimal: [".$this->valorDecimal."], "
				."valorFloat: [".$this->valorFloat."], "
				."\n"."<br/>";
	}
}
?>
