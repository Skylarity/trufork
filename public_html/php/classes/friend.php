<?php
//grab the date parsing function
require_once(dirname(__DIR__) . "/traits/validatedate.php");
require_once(dirname(__DIR__) . "/helpers/filter.php");

/**
 * This Friend is an example of date collected and store  for  trufork
 *
 * @author H Perez <hperezperez.cnm.edu>
 */
class Friend {
	/**
	 *  id for the firstProfile who is generate ; this is a foreign key
	 * @var int $firstProfileId
	 **/
	private $firstProfileId;
	/**
	 *  id for the secondProfiled who is generate; this is a foreign key
	 * @var int $secondProfileId
	 **/
	private $secondProfileId;
	/**
	 * date Friended is date that get friended
	 * @var DateTime $dateFriended
	 **/
	private $dateFriended;


	use validateDate;


	/**
	 * constructor for this Friend
	 *
	 * @param int $newFirstProfile new firstProfile id
	 * @param int $newSecondrofile new secondProfile id
	 * @param DateTime $newDateFriended new dateFriended
	 * @throws UnexpectedValueException if any of the parameters are invalid
	 **/
	public function __construct($newFirstProfileId, $newSecondProfileId, $newDateFriended) {
		try {
			$this->setFirstProfileId($newFirstProfileId);
			$this->setSecondProfileId($newSecondProfileId);
			$this->setDateFriended($newDateFriended);
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
	 * accesor method for firstProfile id
	 * @return int value for firstProfile id
	 **/
	public function getFirstProfileId() {
		return ($this->firstProfileId);
	}

	/**
	 * mutator method for first profileId
	 *
	 * @param int $newFirstProfileId new value  of firstProfile id
	 * @throws  UnexpectedValueException if $new FirstprofileId is not integer
	 **/
	public function setFirstProfileId($newFirstProfileId) {
		// Make sure the ID is not null
		if($newFirstProfileId === null) {
			throw(new InvalidArgumentException("Profile ID must exist"));
		}

		// verify the first Profiled Id is valid
		$newFirstProfileId = filter_var($newFirstProfileId, FILTER_VALIDATE_INT);
		if($newFirstProfileId === false) {
			throw(new UnexpectedValueException("first profile is not valid integer"));
		}
		//verify the firstProfile Id is positive
		if($newFirstProfileId <= 0) {
			throw(new RangeException(" firstProfileId is not positive"));
			// convert and store the firstprofile id
		}
		$this->firstProfileId = intval($newFirstProfileId);
	}

	/**
	 *      accesor method for secondProfileId
	 *
	 * @return int value of secondProfile id
	 **/
	public function getSecondProfileId() {
		return ($this->secondProfileId);
	}

	/**
	 * mutator method for secondProfiledId
	 *@ para int $newSecondProfiledID new value of secondProfileId
	 * @throws UnexpectedVAlueException if $newSecondProfiledID is not a  integer
	 **/
	public function setSecondProfileId($newSecondProfileId) {
		// Make sure the ID is not null
		if($newSecondProfileId === null) {
			throw(new InvalidArgumentException("Profile ID must exist"));
		}

		//verify the secondProfile id is valid
		$newSecondProfileId = filter_var($newSecondProfileId, FILTER_VALIDATE_INT);
		if($newSecondProfileId === false) {
			throw(new UnexpectedValueException("secondProfiledId id is not a valid integer"));
		}
		//convert and store the secondProfiled id
		$this->secondProfileId = intval($newSecondProfileId);
	}

	/**
	 *accssor method for dateFriended
	 *
	 * @return DateTime value of dateFriended
	 **/
	public function getDateFriended() {
		return ($this->dateFriended);
	}

	/**
	 * mutator method for dateFriended
	 *
	 * @param DateTime $newDateFriended new value of dateFriended
	 *
	 * @throws UnexpectedValueException if $newDateProvided is not valid
	 **/
	public function setDateFriended($newDateFriended) {
		// base case : if the date is null, use the current date and time
		if($newDateFriended === null) {
			$this->dateFriended = $newDateFriended;
			return;
		}
		// store the date Friended
		try {
			$newDateFriended = validateDate::validateDate($newDateFriended);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(),
				0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
		$this->dateFriended = $newDateFriended;
	}

	/** toString() magic method
	 *
	 * @return string HTML formatted Friend
	 **/
	public function __toString() {
		//create an HTML formatted friend
		$html = "<p>FirstProfile id" . $this->firstProfileId . "<br/>"
			. "SecondProfile id:" . $this->secondProfileId . "<br/>"
			. "DateFriended:" . $this->dateFriended->format("Y-m-d H:i:s")
			. "<p>";
		return ($html);
	}

	/** inserts friend into DataBase
	 * @param PDO $pdo pointer to pdo connection by reference
	 * @throws PDOException in the event of mySQL errors
	 **/
	public function insert(PDO &$pdo) {
		$query = "INSERT INTO friend(firstProfileId, secondProfileId, dateFriended) VALUES (:firstProfileId,:secondProfileId,:dateFriended)";
		$statement = $pdo->prepare($query);

		$formattedDateFriended = $this->dateFriended->format("Y-m-d H:i:s");
		$parameters = array("firstProfileId" => $this->firstProfileId, "secondProfileId" => $this->secondProfileId, "dateFriended" => $formattedDateFriended);
		$statement->execute($parameters);
	}

	/**
	 * Deletes friend from DB
	 *
	 * @param PDO $pdo pointer to PDO connection by reference
	 * @throws PDOException when MySQL related errors occur
	 **/
	public function delete(PDO &$pdo) {
		if($this->firstProfileId === null || $this->secondProfileId === null) {
			throw(new PDOException("cannot delete a friend that does not exist"));
		}
		$query = "DELETE FROM friend WHERE firstProfileId = :firstProfileId AND secondProfileId = :secondProfileId";
		$statement = $pdo->prepare($query);

		$parameters = array("firstProfileId" => $this->firstProfileId, "secondProfileId" => $this->secondProfileId);
		$statement->execute($parameters);
	}

	public static function getFriendByProfileId(PDO &$pdo, $profileId) {
		// Filter the profile ID
		try {
			$profileId = Filter::filterInt($profileId, "Profile ID", true);
		} catch(InvalidArgumentException $invalidArgument) {
			// Rethrow exception
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// Rethrow exception
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			// Rethrow exception
			throw(new Exception($exception->getMessage(), 0, $exception));
		}

		// Create MySQL query template
		$query = "SELECT * FROM friend WHERE firstProfileId = :profileId";
		$statement = $pdo->prepare($query);

		// Bind variables to placeholders
		$parameters = array("profileId" => $profileId);
		$statement->execute($parameters);

		// Build an array of friends
		$friends = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$friend = new Friend($row["firstProfileId"], $row["secondProfileId"], $row["dateFriended"]);
				$friends[$friends->key()] = $friend;
				$friends->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($friends);
	}

}







