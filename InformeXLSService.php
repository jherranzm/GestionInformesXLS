<?php

require_once('InformeXLS.php');
require_once('Pestanya.php');
require_once('PestanyaService.php');

class InformeXLSService{

	private $username = "root";
	private $password = "illuminatti";

	/**
	 * 
	 */
	public function listAll(){

		$statement = "SELECT id, nombre FROM tbl_informesxls";
		try{
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query = $conn->prepare($statement);
			if(!$query) return false;
			$query->setFetchMode(PDO::FETCH_CLASS, 'InformeXLS');
			if (!$query->execute()) return false;
				
			$result = $query->fetchAll(PDO::FETCH_CLASS, 'InformeXLS');

			$conn = null;

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		return $result;
	}
	
	/**
	 * 
	 */
	public function getByNombre( $nombre ){
	
		$statement = 'SELECT 
			i.id as id 
			, i.nombre as nombre 
			, count(*) as numPestanyes
			FROM tbl_informesxls i
			INNER JOIN tbl_informe_pestanya ip on i.id = ip.informe_id
			WHERE i.nombre LIKE :nombre
			GROUP BY i.nombre
			';
// 			INNER JOIN tbl_informe_pestanya ip on i.id = ip.informe_id
// 			INNER JOIN tbl_pestanyes p on ip.pestanya_id = p.id 
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$query = $conn->prepare($statement);
			if(!$query) return false;
// 			$query->setFetchMode(PDO::FETCH_CLASS, 'InformeXLS');
			if (!$query->execute(array(':nombre' => "%".$nombre."%"))) return false;
	
			$result = $query->fetchAll(PDO::FETCH_CLASS, 'InformeXLS');
				
			$conn = null;
	
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	
		return $result;
	}
	
	
	/**
	 * 
	 * @param int $id
	 */
	public function getById( $id ){
	
		$statement = 'SELECT id, nombre FROM tbl_informesxls WHERE id = :id';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$query = $conn->prepare($statement);
			if(!$query) return false;
			
			$query->setFetchMode(PDO::FETCH_CLASS, 'InformeXLS');
			if (!$query->execute(array(':id' => $id))) return false;
			
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
	 */
	 public function save($informe){
		$statement = 'INSERT INTO tbl_informesxls (Nombre) VALUES (:name)';
		$id = -1;
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			# Prepare the query ONCE
			$stmt = $conn->prepare($statement);
			$stmt->bindParam(':name', utf8_decode($informe->nombre));

			$_nombre = $informe->nombre;

			//     # First insertion
			$stmt->execute();
			$id = $conn->lastInsertId();
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		return $id;
	 }
	
	
	/**
	 * 
	 * @param InformeXLS $informeXLS
	 */
	public function update( InformeXLS $informeXLS ){
	
		$statement = '
		UPDATE tbl_informesxls p
		SET
		p.nombre = :nombre
		WHERE p.id = :id';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			
			$query = $conn->prepare($statement);
			if(!$query) return false;
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'informeXLS');
			if (!$query->execute(array(':id' => $informeXLS->id,
					':nombre' => utf8_decode($informeXLS->nombre)))) return false;
			return true;
				
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
			return false;
		}
	
		return false;
	}
	
	/**
	 * 
	 * @param int $idInforme
	 * @return boolean|multitype:
	 */
	public function getPestanyesByInforme( $idInforme ){
	
		$statement = 'call Pestanyes_ByInforme( ? );';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			
			$query = $conn->prepare($statement);
			
			if(!$query) return false;
			
			$query->bindParam(1, $idInforme, PDO::PARAM_INT);
			
			if (!$query->execute()) return false;
	
			$result = $query->fetchAll(PDO::FETCH_CLASS, 'pestanya');
	
			$conn = null;
	
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
		return $result;
	}
	

	/**
	 * 
	 * @param int $idInforme
	 */
	public function getPestanyesNoEnInforme( $idInforme ){
	
		$statement = 'call Pestanyes_NoEnInforme( ? );';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			
			$query = $conn->prepare($statement);
			
			if(!$query) return false;
			
			$query->bindParam(1, $idInforme, PDO::PARAM_INT);
			
			if (!$query->execute()) return false;
	
			$result = $query->fetchAll(PDO::FETCH_CLASS, 'pestanya');
	
			$conn = null;
	
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
		return $result;
	}
	
	
	/**
	 *
	 * @param int $idInforme
	 */
	public function deletePestanyesEnInforme( $idInforme ){
	
		$statement = 'call Borrar_Pestanyes_EnInforme( ? );';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
				
			$query = $conn->prepare($statement);
				
			if(!$query) return false;
				
			$query->bindParam(1, $idInforme, PDO::PARAM_INT);
				
			if (!$query->execute()) return false;
	
			$conn = null;
	
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	
		return true;
	}
	
	
	/**
	 * 
	 * @param int $idInforme
	 * @param int $idPestanya
	 * @param int $order
	 */
	public function addPestanyaEnInforme( $idInforme, $idPestanya, $order ){
	
		$statement = 'call Add_Pestanya_EnInforme( ?, ?, ? );';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
				
			$query = $conn->prepare($statement);
				
			if(!$query) return false;
				
			$query->bindParam(1, $idInforme, PDO::PARAM_INT);
			$query->bindParam(2, $idPestanya, PDO::PARAM_INT);
			$query->bindParam(3, $order, PDO::PARAM_INT);
				
			if (!$query->execute()) return false;
	
			$conn = null;
	
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	
		return true;
	}
}
?>