<?php

/**
 * This class contains data and functionality for restaurant violations
 *
 * @author Skyler Rexroad
 */

require_once(dirname(__DIR__) . "helpers/filter.php");

class Violation {

	/**
	 * ID of the violation
	 * @var int $violationId
	 */
	private $violationId;

	/**
	 * ID of the restaurant that got the violation
	 * @var int $restaurantId
	 */
	private $restaurantId;

	/**
	 * Code of the violation
	 * @var int $violationCode
	 */
	private $violationCode;

	/**
	 * Description of the violation
	 * @var string $violationDesc
	 */
	private $violationDesc;

	/**
	 * Inspection memo that describes what was wrong
	 * @var string $inspectionMemo
	 */
	private $inspectionMemo;

	/**
	 * Serial number of the violation
	 * @var string $serialNum
	 */
	private $serialNum;

	/**
	 * Constructor for a violation
	 *
	 * @param int $violationId ID of the violation
	 * @param int $restaurantId ID of the restaurant that has the violation
	 * @param int $violationCode Code of the violation
	 * @param string $violationDesc Description of the violation
	 * @param string $inspectionMemo Inspection memo that describes what was wrong
	 * @param string $serialNum Serial number of the violation
	 * @throws InvalidArgumentException If the data is invalid
	 * @throws RangeException If the data is out of bounds
	 * @throws Exception For all other cases
	 */
	public function __construct($violationId, $restaurantId, $violationCode, $violationDesc, $inspectionMemo, $serialNum) {
		try {
			$this->setViolationId($violationId);
			$this->setRestaurantId($restaurantId);
			$this->setViolationCode($violationCode);
			$this->setViolationDesc($violationDesc);
			$this->setInspectionMemo($inspectionMemo);
			$this->setSerialNum($serialNum);
		} catch(InvalidArgumentException $invalidArgument) {
			// Rethrow exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// Rethrow exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $e) {
			// Rethrow exception to the caller
			throw(new Exception($e->getMessage(), 0, $e));
		}
	}

	/**
	 * Accessor for serial number
	 *
	 * @return string
	 */
	public function getSerialNum() {
		return $this->serialNum;
	}

	/**
	 * Mutator for serial number (max length 12)
	 *
	 * @param string $serialNum
	 */
	public function setSerialNum($serialNum) {
		$this->serialNum = Filter::filterString($serialNum, 12, "Serial number");
	}

	/**
	 * Accessor for violation ID
	 *
	 * @return int
	 */
	public function getViolationId() {
		return $this->violationId;
	}

	/**
	 * Mutator for violation ID
	 *
	 * @param int $violationId
	 */
	public function setViolationId($violationId) {
		$this->violationId = Filter::filterID($violationId, "Violation ID");
	}

	/**
	 * Accessor for restaurant ID
	 *
	 * @return int
	 */
	public function getRestaurantId() {
		return $this->restaurantId;
	}

	/**
	 * Mutator for restaurant ID
	 *
	 * @param int $restaurantId
	 */
	public function setRestaurantId($restaurantId) {
		$this->restaurantId = Filter::filterInt($restaurantId, "Restaurant ID");
	}

	/**
	 * Accessor for the violation code
	 *
	 * @return int
	 */
	public function getViolationCode() {
		return $this->violationCode;
	}

	/**
	 * Mutator for the violation code
	 *
	 * @param int $violationCode
	 */
	public function setViolationCode($violationCode) {
		$this->violationCode = Filter::filterInt($violationCode, "Violation code");
	}

	/**
	 * Accessor for the violation description
	 *
	 * @return string
	 */
	public function getViolationDesc() {
		return $this->violationDesc;
	}

	/**
	 * Mutator for the violation description (max length 64)
	 *
	 * @param string $violationDesc
	 */
	public function setViolationDesc($violationDesc) {
		$this->violationDesc = Filter::filterString($violationDesc, 64, "Violation description");
	}

	/**
	 * Accessor for the inspection memo
	 *
	 * @return string
	 */
	public function getInspectionMemo() {
		return $this->inspectionMemo;
	}

	/**
	 * Mutator for the inspection memo (max length 256)
	 *
	 * @param string $inspectionMemo
	 */
	public function setInspectionMemo($inspectionMemo) {
		$this->inspectionMemo = Filter::filterString($inspectionMemo, 256, "Inspection memo");
	}

	/*
	 * violationId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	 * restaurantId INT UNSIGNED NOT NULL,
	 * violationCode INT(12) NOT NULL,
	 * violationDesc VARCHAR(64) NOT NULL,
	 * inspectionMemo VARCHAR(256) NOT NULL,
	 * serialNum VARCHAR(12) NOT NULL
	 */

	/**
	 * Inserts this violation into MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function insert(PDO &$pdo) {
		// Make sure this is a new violation
		if($this->restaurantId !== null) {
			throw(new PDOException("Not a new violation"));
		}

		// Create query template
		$query = "INSERT INTO violation(violationId, restaurantId, violationCode, violationDesc, inspectionMemo, serialNum) VALUES(:violationId, :restaurantId, :violationCode, :violationDesc, :inspectionMemo, :serialNum)";
		$statement = $pdo->prepare($query);


		// Bind the member variables to the placeholders in the template
		$parameters = array("violationId" => $this->getViolationId(), "restaurantId" => $this->getRestaurantId(), "violationCode" => $this->violationCode, "violationDesc" => $this->getViolationDesc(), "inspectionMemo" => $this->getInspectionMemo(), "serialNum" => $this->getSerialNum());
		$statement->execute($parameters);

		// Update the null violation ID with what MySQL has generated
		$this->setViolationId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this violation from MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this violation already exists
		if($this->getViolationId() === null) {
			throw(new PDOException("Unable to delete a violation that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM violation WHERE violationId = :violationId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("violationId" => $this->getViolationId());
		$statement->execute($parameters);
	}

	/**
	 * Updates this violation in MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function update(PDO &$pdo) {
		// Make sure this violation already exists
		if($this->getViolationId() === null) {
			throw(new PDOException("Unable to update a violation that does not exist"));
		}

		// Create query template
		$query = "UPDATE violation SET violationId = :violationId, restaurantId = :restaurantId, violationCode = :violationCode, violationDesc = :violationDesc, inspectionMemo = :inspectionMemo, serialNum = :serialNum WHERE violationId = :violationId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the template
		$parameters = array("violationId" => $this->getViolationId(), "restaurantId" => $this->getRestaurantId(), "violationCode" => $this->violationCode, "violationDesc" => $this->getViolationDesc(), "inspectionMemo" => $this->getInspectionMemo(), "serialNum" => $this->getSerialNum());
		$statement->execute($parameters);
	}

	/**
	 * Gets the violation by restaurant ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $violationId violation ID to search for
	 * @return mixed Violation found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getViolationById(PDO &$pdo, $violationId) {
		// Sanitize the ID before searching
		$violationId = filter_var($violationId, FILTER_SANITIZE_NUMBER_INT);
		if($violationId === false) {
			throw(new PDOException("Violation ID is not an integer"));
		}
		if($violationId <= 0) {
			throw(new PDOException("Violation ID is not positive"));
		}

		// Create query template
		$query = "SELECT violationId, restaurantId, violationCode, violationDesc, inspectionMemo, serialNum FROM violation WHERE violationId = :violationId";
		$statement = $pdo->prepare($query);

		// Bind ID to placeholder
		$parameters = array("violationId" => $violationId);
		$statement->execute($parameters);

		// Grab the violation from MySQL
		try {
			$violation = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				// new Violation($violationId, $restaurantId, $violationCode, $violationDesc, $inspectionMemo, $serialNum);
				$violation = new Violation($row["violationId"], $row["restaurantId"], $row["violationCode"], $row["violationDesc"], $row["inspectionMemo"], $row["serialNum"]);
			}
		} catch(Exception $e) {
			// If the row couldn't be converted, rethrow it
			throw(new PDOException($e->getMessage(), 0, $e));
		}

		return ($violation);
	}

	/**
	 * Gets the violation by an attribute and integer value
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $attribute violation attribute to search for
	 * @param int $int integer value to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getViolationByInt(PDO &$pdo, $attribute, $int) {
		// Sanitize the int before searching
		$int = filter_var($int, FILTER_SANITIZE_NUMBER_INT);
		if($int === false) {
			throw(new PDOException("$attribute is not an integer"));
		}
		if($int <= 0) {
			throw(new PDOException("$attribute is not positive"));
		}

		// Create query template
		$query = "SELECT violationId, restaurantId, violationCode, violationDesc, inspectionMemo, serialNum FROM violation WHERE $attribute = :int";
		$statement = $pdo->prepare($query);

		// Bind int to placeholder
		$parameters = array("int" => $int);
		$statement->execute($parameters);

		// Build an array of violations
		$violations = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$violation = new Violation($row["violationId"], $row["restaurantId"], $row["violationCode"], $row["violationDesc"], $row["inspectionMemo"], $row["serialNum"]);
				$violations[$violations->key()] = $violation;
				$violations->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($violations);
	}

	/**
	 * Gets the violation by an attribute and string value
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $attribute violation attribute to search for
	 * @param string $string string value to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getViolationByString(PDO &$pdo, $attribute, $string) {
		// Sanitize the string before searching
		$string = trim($string);
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(empty($string)) {
			throw(new PDOException("$attribute is invalid"));
		}

		// Create query template
		$query = "SELECT violationId, restaurantId, violationCode, violationDesc, inspectionMemo, serialNum FROM violation WHERE $attribute = :string";
		$statement = $pdo->prepare($query);

		// Bind string to placeholder
		$parameters = array("string" => $string);
		$statement->execute($parameters);

		// Build an array of violations
		$violations = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$violation = new Violation($row["violationId"], $row["restaurantId"], $row["violationCode"], $row["violationDesc"], $row["inspectionMemo"], $row["serialNum"]);
				$violations[$violations->key()] = $violation;
				$violations->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($violations);
	}

	public static function getAllViolations(PDO &$pdo) {
		// Create query template
		$query = "SELECT violationId, restaurantId, violationCode, violationDesc, inspectionMemo, serialNum FROM violation";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Build an array of violations
		$violations = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$violation = new Violation($row["violationId"], $row["restaurantId"], $row["violationCode"], $row["violationDesc"], $row["inspectionMemo"], $row["serialNum"]);
				$violations[$violations->key()] = $violation;
				$violations->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($violations);
	}

}