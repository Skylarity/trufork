<?php

/**
 * Profile class for trufork.com
 *
 * This is the profile entity that sets Profile Id, userId and user email
 * @author Kenneth Anthony <kchavez68@cnm.edu>
 */
class Profile {
	/**
	 * id for TruFork; this is a primary key
	 * @var int $profileId
	 */
	private $profileId;

	/**
	 * user id that saved end user uses to vote
	 * @var $userId ; this is a primary key
	 **/
	private $userId;

	/** email is an attribute
	 * @var $email ; this is stored in profile settings
	 **/
	private $email;


	/**
	 * Constructor method for user class
	 *
	 * @param mixed $newProfileId id for this class
	 * @param int $newUserId id for profile id
	 * @param $newEmail
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 * @throws Exception if some other exception is thrown
	 *
	 */

	public function __construct($newProfileId, $newUserId, $newEmail) {
		try {
			$this->setProfileId($newProfileId);
			$this->setUserId($newUserId);
			$this->setEmail($newEmail);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow range exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			// rethrow generic exception
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profileId
	 *
	 * @return int mixed value of profileId
	 */
	public function getProfileId() {
		return $this->profileId;
	}


	/**
	 * mutator method for profile id
	 *
	 * @param mixed $newProfileId
	 * @throws InvalidArgumentException if $profileId is not integer
	 * @throws RangeException if $newProfileId is not positive
	 */
	public function setProfileId($newProfileId) {
		// base case: if the profile id is null, this a new profile id without a mySQL assigned id (yet)
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		//verify the profile id is valid
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException ("profile id is not a valid integer"));
		}

		// verify if the profile id is valid
		if($newProfileId <= 0) {
			throw(new RangeException("profile id is not positive"));
		}

		//convert and store the profile Id
		$this->profileId = intval($newProfileId);
	}

	/**
	 * accessor method for userId
	 *
	 * @return int user id
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * mutator method for user id
	 *
	 * @param mixed $newUserId
	 * @throws InvalidArgumentException if $userId is not a integer
	 * @throws RangeException if $UserId is not positive
	 */

	public function setUserId($newUserId) {
		// verify the profile id is valid
		$newUserId = filter_var($newUserId, FILTER_VALIDATE_INT);
		if($newUserId === false) {
			throw(new InvalidArgumentException("user id is not a valid integer"));
		}

		// verify the user id is positive
		If($newUserId <= 0) {
			throw(new RangeException("user id is not positive"));
		}
		//convert and store user id
		$this->userId = intval($newUserId);
	}


	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 */

	public function getEmail() {
		return ($this->email);
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newEmail new value of email
	 * @throws InvalidArgumentException if $newEmail is not a integer or not positive
	 * @throws RangeException if $newEmail is not Positive
	 */

	public function setEmail($newEmail) {
		// verify if email is positive
		if($newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL)) ;
		if($newEmail === false) {
			throw(new InvalidArgumentException ("email is not a valid email"));
		}

		//verify the email is positive
		if(strlen($newEmail) > 64) {
			throw(new RangeException("email is too long"));
		}

		// convert and store email id
		$this->email = $newEmail;
	}

	/**
	 * insert this profile id into mySQL
	 *
	 * pdo Represents a connection between PHP and a database server.
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL relates errors occur
	 *
	 **/
	public function insert(PDO &$pdo) {
		// Make sure this is a new profile
		if($this->profileId !== null) {
			throw(new PDOException("Not a new profile"));
		}


		// create a query template
		$query = "INSERT INTO profile(userId, email) VALUES(:userId, :email)";
		$statement = $pdo->prepare($query);

		// bind profileId to placeholders in template
		$parameters = array("userId" => $this->getUserId(), "email" => $this->getEmail());
		$statement->execute($parameters);

		// Update the null profile ID with what MySQL has generate
		$this->setProfileId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this Profile from MySQL
	 *
	 * Represents a connection between PHP and a database server.
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this restaurant already exists
		if($this->getProfileId() === null) {
			throw(new PDOException("Unable to delete a profile that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the place holders in the template
		$parameters = array("profileId" => $this->getProfileId());
		$statement->execute($parameters);
	}


	/**
	 * Updates this profile in MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function update(PDO &$pdo) {
		// Make sure this restaurant exists
		if($this->getProfileId() === null) {
			throw(new PDOException("Unable to update a profile that does not exist"));
		}

		// Create query template
		$query = "UPDATE profile SET profileId = :profileId, userId = :userId, email = :email WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("profileId" => $this->getProfileId(), "userId" => $this->getUserId(), "email" => $this->getEmail());
		$statement->execute($parameters);
	}

	/**
	 * Gets the profile by profile ID
	 *
	 * @param PDO $pdo $pd pointer to the PDO connection, by reference
	 * @param int $profileId profile ID to search for
	 * @return mixed profile found null if not found
	 */
	public static function getProfileByProfileId(PDO &$pdo, $profileId) {
		// sanitize the profileId before searching
		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);
		if($profileId === false) {
			throw(new PDOException("profile id is not an integer"));
		}
		if($profileId <= 0) {
			throw(new PDOException("profile id is not positive"));
		}

		// create query template
		$query = "SELECT profileId, userId, email FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the profile id to the place holder in the template
		$parameters = array("profileId" => $profileId);
		$statement->execute($parameters);

		// grab the profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["userId"], $row["email"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * Gets the profile by userId
	 *
	 * @param PDO $pdo get profile by User Id
	 * @param int $userId profile ID to search for
	 * @return mixed profile found null if not found
	 * PDO $pod pointer to the PDO connection, by reference
	 */
	public static function getProfileByUserId(PDO &$pdo, $userId) {
		// sanitize the profileId before searching
		$userId = filter_var($userId, FILTER_VALIDATE_INT);
		if($userId === false) {
			throw(new PDOException("user id is not an integer"));
		}
		if($userId <= 0) {
			throw(new PDOException("user id is not positive"));
		}

		// create query template
		$query = "SELECT profileId, userId, email FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the profile id to the place holder in the template
		$parameters = array("profileId" => $userId);
		$statement->execute($parameters);

		// grab the profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["userId"], $row["email"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * gets profile by email
	 *
	 * @param PDO $pdo pointer for the pdo connection
	 * @param string $email for user email
	 * @throws PDOException for mySQL errors
	 * @return Profile $profile profile
	 */
	public static function getProfileByEmail(PDO &$pdo, $email) {
		// sanitize the email before searching
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if($email === false) {
			throw(new PDOException("email id is not an email"));
		}

		// create query template
		$query = "SELECT profileId, userId, email FROM profile WHERE email = :email";
		$statement = $pdo->prepare($query);

		// bind the email to the place holder in the template
		$parameters = array("email" => $email);
		$statement->execute($parameters);

		// grab the profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["userId"], $row["email"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}
}