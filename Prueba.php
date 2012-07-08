<?php
class Test{

	public $id;
	public $fecha;
	public $fecha2;
	public $textoLargo;
	public $decimal;
	public $float;
	
	

	public function toString(){
		return "Id: [".$this->id."],  "
				."Fecha: [".$this->fecha."], "
				."Fecha2: [".$this->fecha2."], "
				."textoLargo: [".$this->textoLargo."], "
				."decimal: [".$this->decimal."], "
				."float: [".$this->float."], "
				."\n"."<br/>";
	}
}
?>
