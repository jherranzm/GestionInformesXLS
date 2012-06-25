<?php
class InformeXLS{

	public $id;
	public $nombre;
	public $numPestanyes = 0;
	public $listaPestanya = array();
	
	

	public function toString(){
		return "Id: [".$this->id."],  "
				."Nombre: [".$this->nombre."] "
				."\n"."<br/>";
	}
}
?>
