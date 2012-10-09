<?php
/**
 * Mother Model!
 * Bir PDO Statement'Ä±nÄ± doÄŸrudan object almaya yarar.
 * Ã‡ok lezzetli oldu, bundan da bir proje olur ;)
 * 
 * @author Bahadir
 * @version 0.5
 *
 */

class motherModel {

	/**
	 * 
	 * @param PDOStatement $statement
	 */
	function __construct($statement) {
		$statement->setFetchMode(PDO::FETCH_INTO,$this);
		return $statement->fetch(PDO::FETCH_INTO); 
		
	}
}

class wordModel extends motherModel {
	
}
?>