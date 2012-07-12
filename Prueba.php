<?php

interface JsonSerializable{ 
    public function jsonSerialize(); 
  } 


class Prueba implements JsonSerializable{

	public $id;
	public $fechaLarga;
	public $fecha;
    public $hora;
    public $textoCorto;
	public $textoLargo;
	public $valorDecimal;
	public $valorFloat;
    public $activo;
	
	

	public function toString(){
		return "Id: [".$this->id."],  "
				."FechaLarga: [".$this->fechaLarga."], "
				."Fecha: [".$this->fecha."], "
                ."Hora: [".$this->hora."], "
                ."textoCorto: [".$this->textoCorto."], "
                ."textoLargo: [".$this->textoLargo."], "
				."valorDecimal: [".$this->valorDecimal."], "
				."valorFloat: [".$this->valorFloat."], "
				."activo: [".$this->activo."] "
				."\n"."<br/>";
	}
    
    public function jsonSerialize(){
        return get_object_vars($this);
    }
}
?>
