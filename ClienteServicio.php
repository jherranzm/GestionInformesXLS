<?php


class ClienteServicio{

    public $cuc;
    public $cif;
    public $nombre;
    public $tipoServicio;
    public $descTipoServicio;
    public $fechaInicioPeriodo;
    public $fechaFinPeriodo;

    public function toString(){
        return "ClienteServicio: "
        .$this->cuc."\t".$this->cif
        ."\t".$this->nombre
        ."\t".$this->tiposervicio
        ."\t".$this->descTipoServicio
        ."\t".$this->fechaInicioPeriodo
        ."\t".$this->fechaFinPeriodo
        ."\n"."<br/>";
    }
}

?>