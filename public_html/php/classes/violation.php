<?php

/**
 * This class contains data and functionality for restaurant violations
 *
 * @author Skyler Rexroad
 */

require_once(dirname(__DIR__) . "helpers/filter.php");
require_once(dirname(__DIR__) . "helpers/pdo-operations.php");

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
	 * @return int
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

	public function insert() {
		// Make sure this is a new violation
		if($this->restaurantId !== null) {
			throw(new PDOException("Not a new violation"));
		}

		$fields = [
			"violationId",
			"restaurantId",
			"violationCode",
			"violationDesc",
			"inspectionMemo",
			"serialNum"
		];

		$values = [
			$this->getViolationId(),
			$this->getRestaurantId(),
			$this->getViolationCode(),
			$this->getViolationDesc(),
			$this->getInspectionMemo(),
			$this->getSerialNum()
		];

		$this->setViolationId(PdoOperations::insert(&$pdo, $fields, $values));
	}

}