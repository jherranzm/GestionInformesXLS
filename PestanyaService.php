<?php

include_once('Pestanya.php');

class PestanyaService{

	private $username = "root";
	private $password = "illuminatti";

	public function listAll(){

		$statement = '
			SELECT 
				p.id as id, 
				p.nombre as nombre, 
				p.rango as rango, 
				p.numfilainicial as numfilainicial, 
				p.consulta_id as consultaid,
				c.nombre as nombreConsulta
			 
			FROM tbl_Pestanyes p
			LEFT JOIN tbl_ConsultasSQL c ON p.consulta_id = c.id
			';
		error_log( $statement);
		try{
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");

			$query = $conn->prepare($statement);
			if(!$query) return false;
				
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'pestanya');
			if (!$query->execute()) return false;
			
			$result = $query->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'pestanya');
				
// 			$result = $conn->query($statement);

// 			# Map results to object
// 			$result->setFetchMode(PDO::FETCH_CLASS, 'Pestanya');

// 			while($pestanya = $result->fetch()) {
// 				echo $pestanya->toString();
// 			}

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
		return $result;
	}
	
	public function getByNombre( $nombre ){
	
		$statement = '
			SELECT 
				p.id as id, 
				p.nombre as nombre, 
				p.rango as rango, 
				p.numfilainicial as numfilainicial, 
				p.consulta_id as consultaid,
				c.nombre as nombreConsulta
			 
			FROM tbl_Pestanyes p
			LEFT JOIN tbl_ConsultasSQL c ON p.consulta_id = c.id
			WHERE p.nombre LIKE :nombre';
		error_log( $statement);
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$query = $conn->prepare($statement);
			if(!$query) return false;
			
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'pestanya');
			if (!$query->execute(array(':nombre' => "%".$nombre."%"))) return false;
	
			$result = $query->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'pestanya');
			
// 			foreach($result as $pestanya){
// 				error_log("Pestanya:".$pestanya->toString());
// 			}
				
			$conn = null;
	
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	
		return $result;
	}
	
	
	/**
	 *
	 * @param int $idConsulta
	 */
	public function getById( $_id ){
	
		$statement = '
			SELECT 
				p.id as id, 
				p.nombre as nombre, 
				p.rango as rango, 
				p.numfilainicial as numfilainicial, 
				p.consulta_id as consultaid,
				c.nombre as nombreConsulta
			 
			FROM tbl_Pestanyes p
			LEFT JOIN tbl_ConsultasSQL c ON p.consulta_id = c.id
			WHERE p.id = :id';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$query = $conn->prepare($statement);
			if(!$query) return false;
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'pestanya');
			if (!$query->execute(array(':id' => $_id))) return false;
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
	 * @param int $idConsulta
	 */
	public function update( $pestanya ){
	
		$statement = '
		UPDATE tbl_Pestanyes p
		SET
			p.nombre = :nombre, 
			p.rango = :rango, 
			p.numfilainicial = :numfilainicial, 
			p.consulta_id = :consultaid
		WHERE p.id = :id';
		try {
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$query = $conn->prepare($statement);
			if(!$query) return false;
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'pestanya');
			if (!$query->execute(array(':id' => $pestanya->id, 
										':nombre' => utf8_decode($pestanya->nombre),
										':rango' => utf8_decode( $pestanya->rango),
										':numfilainicial' => $pestanya->numfilainicial,
										':consultaid' => $pestanya->consultaid,))) return false;
			return true;
			
	
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
			return false;
		}
	
		return false;
	}
	
}
?>