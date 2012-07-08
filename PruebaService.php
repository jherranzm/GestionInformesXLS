<?php

include_once('Prueba.php');

class PruebaService{

	private $username = "root";
	private $password = "illuminatti";

	
	/**
	 * 
	 */
	public function listAll(){

		$statement = "SELECT id, fecha, fecha2, textoLargo, valorDecimal, valorFloat  FROM tbl_test";
		try{
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query = $conn->prepare($statement);
			if(!$query) return false;
			
			$query->setFetchMode(PDO::FETCH_CLASS, 'Prueba');
			if (!$query->execute()) return false;
			
			$result = $query->fetchAll(PDO::FETCH_CLASS, 'Prueba');
				
			$conn = null;
				
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
		return $result;
	}
}

?>