<?php

require_once('Prueba.php');

class PruebaService{

	private $username = "root";
	private $password = "illuminatti";
    
    /**
     * 
     */
    public function findById( $id){

        $statement = "SELECT 
        id, fechaLarga, fecha, hora, textoCorto, textoLargo, valorDecimal, valorFloat, activo  
        FROM tbl_test
        WHERE id = :id";
        try{
            $conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $conn->prepare($statement);
            if(!$query) return false;
            
            $query->bindParam(1, $id, PDO::PARAM_INT, 40000); 
            
            $query->setFetchMode(PDO::FETCH_CLASS, 'Prueba');
            if (!$query->execute(array(':id' => $id))) return false;
            
            $result = $query->fetch();
            if (!$result) return false;
                
            $conn = null;
                
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        
        return $result;
    }
	
	/**
	 * 
	 */
	public function listAll(){

		$statement = "SELECT 
		id, fechaLarga, fecha, hora, textoCorto, textoLargo, valorDecimal, valorFloat, activo  
		FROM tbl_test";
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
	
	public function addNewTest($texto, $num1, $num2){
		$statement = "CALL addNewTest(?, ?, ?, ?, @outParameter)";
		$numNewTest = -1;
        $activo = rand(0,1);
		try{
			$conn = new PDO('mysql:host=localhost;dbname=977r', $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query = $conn->prepare($statement);
            
			$query->bindParam(1, utf8_decode($texto), PDO::PARAM_STR, 40000); 
			$query->bindParam(2, $num1, PDO::PARAM_INT, 40000); 
			$query->bindParam(3, $num2, PDO::PARAM_INT, 40000); 
            $query->bindParam(4, $activo, PDO::PARAM_INT, 40000); 
			if(!$query) return false;
			
			if (!$query->execute()) return false;
            $query->closeCursor();
			
            $query = $conn->prepare("SELECT @outParameter");
            if (!$query->execute()) return false;
            $numNewTest = $query->fetchColumn();
            
            echo "ID [".$numNewTest."]"."\n<br/>";     
                  
			$conn = null;
				
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
		return $numNewTest;
	}
}

?>