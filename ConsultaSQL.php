<?php


class ConsultaSQL{

	public $id;
	public $nombre;
	public $definicion;

	public function toString(){
		return "ConsultaSQL: ".$this->id."\t".$this->nombre."\t".$this->definicion."\n"."<br/>";
	}
}

?>