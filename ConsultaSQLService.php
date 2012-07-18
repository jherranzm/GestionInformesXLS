<?php

include_once('ConsultaSQL.php');

class ConsultaSQLService{

	private $username = "root";
	private $password = "illuminatti";

	
	/**
	 * 
	 */
	public function listAll(){

		$statement = "SELECT id, nombre, definicion FROM tbl_consultasSQL";
		try{
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$query = $conn->prepare($statement);
			if(!$query) return false;
			
			$query->setFetchMode(PDO::FETCH_CLASS, 'ConsultaSQL');
			if (!$query->execute()) return false;
			
			$result = $query->fetchAll(PDO::FETCH_CLASS, 'ConsultaSQL');
				
			$conn = null;
				
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
		return $result;
	}

	/**
	 *
	 * @param ConsultaSQL $consulta
	 */
	public function save( ConsultaSQL $consulta ){


		$statement = 'INSERT INTO tbl_consultasSQL (Nombre, Definicion) VALUES (:name, :sql)';
        $id = -1;
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");

			# Prepare the query ONCE
			$stmt = $conn->prepare($statement);

            $_nombre = utf8_decode($consulta->nombre);
            $_definicion = utf8_decode($consulta->definicion);
            
            $stmt->bindParam(':name', $_nombre);
            $stmt->bindParam(':sql', $_definicion);

            if(!$stmt->execute()) return false ;
            $id = $conn->lastInsertId();
            
            $conn = null;
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
        return $id;
	}

	/**
     * 
	 * Retorna true o false en funcion de si ha podido o no
     * modificar la ConsultaSQL
     * 
	 * @param ConsultaSQL $consulta
	 */
	public function update( ConsultaSQL $consulta ){
	
	
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
	
			# Prepare the query ONCE
			$stmt = $conn->prepare('UPDATE tbl_consultasSQL SET Nombre = :name, Definicion =  :sql WHERE id = :id');
			
            $_nombre = utf8_decode($consulta->nombre);
            $_definicion = utf8_decode($consulta->definicion);
            
            $stmt->bindParam(':name', $_nombre);
            $stmt->bindParam(':sql', $_definicion);
            $stmt->bindParam(':id', $consulta->id);
            
			if(!$stmt->execute() ) return false;
            
            return true;
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
            return false;
		}
        return false;
	}
	
	/**
	 *
     *  Retorna un objeto ConsultaSQL
     * 
	 * @param int $idConsulta
	  */
	  public function getById( $idConsulta ){

	  	$statement = 'SELECT id, nombre, definicion FROM tbl_consultasSQL WHERE id = :id';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            
			$query = $conn->prepare($statement);
			if(!$query) return false;
			$query->setFetchMode(PDO::FETCH_CLASS, 'ConsultaSQL');
			if (!$query->execute(array(':id' => $idConsulta))) return false;
			# Map results to object
			$cons = $query->fetch();
			if (!$cons) return false;
				
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		return $cons;
	}
	
	/**
	 *
     * Retorna true o false dependiendo de si ha podido o no borrar la consulta
	 * @param int $idConsulta
	 */
	public function delete( $idConsulta ){
	
		$statement = 'DELETE FROM tbl_consultasSQL WHERE Id = :id';
		error_log("Borrando la consulta:".$idConsulta);
		
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            
			$query = $conn->prepare($statement);
			if(!$query) return false;
			if (!$query->execute(array(':id' => $idConsulta))) return false;
			error_log("Consulta ".$idConsulta." borrada!");
			return true;
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	
		return false;
	}
	
	public function listAllToTable(){
		
		$br = "<br/>";
		$iniDiv = "<div>";
		$finDiv = "</div>";
		$iniTable = "<table>";
		$finTable = "</table>";
		$iniTr = "<tr>";
		$finTr = "</tr>";
		$iniTh = "<th>";
		$finTh = "</th>";
		$iniTd = "<td>";
		$finTd = "</td>";
		
		$_img_delete = "<img src='img/Bullet-Delete-32.png'>";
		$_img_edit   = "<img src='img/File-Edit-32.png'>";
		$_controller = "Gestor.ConsultaSQL.php";
		
		$res = "";
		
	
		try{
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
			$result = $conn->query('SELECT id, nombre, definicion FROM tbl_consultasSQL');
	
			# Map results to object
			$result->setFetchMode(PDO::FETCH_CLASS, 'ConsultaSQL');
	
			if($result->rowCount()>0){
				$res = $res.$iniDiv."\n";
				$res = $res.$iniTable."\n";
				
				$res = $res.$iniTr."\n";
				$res = $res.$iniTh."Edit".$finTh."\n";
				$res = $res.$iniTh."Borrar".$finTh."\n";
				$res = $res.$iniTh."Nombre".$finTh."\n";
				$res = $res.$iniTh."Definicion".$finTh."\n";
				$res = $res.$finTr."\n";
				
				while($cons = $result->fetch()) {
					$res = $res.$iniTr."\n";
					$res = $res.$iniTd."<a class='editConsultaSQL' id='edit_".$cons->id."' href='".$_controller."?op=edit&id=".$cons->id."'>".$_img_edit."</a>".$finTd."\n";
					$res = $res.$iniTd."<a class='delConsultaSQL'  id='del_".$cons->id."' href='".$_controller."?op=del&id=".$cons->id."'>".$_img_delete."</a>".$finTd."\n";
					$res = $res.$iniTd.$cons->nombre.$finTd."\n";
					$res = $res.$iniTd.$cons->definicion.$finTd."\n";
					$res = $res.$finTr."\n";
					//echo $cons->toString();
					//alert("**".$cons->toString()."**");
				}
				$res = $res.$finTable."\n";
				$res = $res.$finDiv."\n";
				
			}
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
		return $res;
	}
	
	public function getByNombre( $nombre ){
	
		$statement = 'SELECT id, nombre, definicion FROM tbl_consultasSQL WHERE nombre LIKE :nombre';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            
			$query = $conn->prepare($statement);
			if(!$query) return false;
			$query->setFetchMode(PDO::FETCH_CLASS, 'ConsultaSQL');
			if (!$query->execute(array(':nombre' => "%".$nombre."%"))) return false;

			$result = $query->fetchAll(PDO::FETCH_CLASS, 'ConsultaSQL');
			
			$conn = null;
				
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	
		return $result;
	}
}

?>