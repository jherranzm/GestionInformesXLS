<?php
class Respuesta{

	public $codigo;
	public $mensaje;
	public $listaConsultaSQL = array();
	public $listaPestanya = array();
	public $listaPestanyaDisponible = array();
	public $listaInformeXLS = array();
	
	public function toString(){
		return "Código: [".$this->codigo."], "
				."Mensaje:[".$this->mensaje."]"."<br/>";
	}
}

?>