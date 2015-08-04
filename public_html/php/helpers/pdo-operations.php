<?php

/**
 * This class contains methods to insert, update, delete, and get from a PDO object
 *
 * @author Skyler Rexroad
 */
class PdoOperations {

	public static function insert(PDO &$pdo, $fields, $values) {
		// Create string of fields
		$fieldStr = "";
		foreach($fields as $field) {
			$fieldStr = $fieldStr . $field . ", ";
		}
		$fieldStr = substr(0, strlen($fieldStr) - 2);

		// Create string of values
		$valueStr = "";
		foreach($values as $value) {
			$valueStr = $valueStr . $value . ", ";
		}
		$valueStr = substr(0, strlen($valueStr) - 2);

		// Create query template
		$query = "INSERT INTO restaurant($fieldStr) VALUES($valueStr)";

		// Create and execute statement
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Return the null object ID with what MySQL has generated
		return (intval($pdo->lastInsertId()));
	}

}