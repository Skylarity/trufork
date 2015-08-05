<?php

/**
 * Created by PhpStorm.
 * User: Kenneth Anthony
 * Date: 8/2/2015
 * Time: 8:24 PM
 */

class Profile {
	/**
	 * id for TruFork; this is a primary key
	 * @var   int $profileId
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
	 * accessor method for profileId
	 *
	 * @return int mixed value of profileId
	 */
	public function getProfileId() {
		return $this->profileId;
	}


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
	 * mutator method for profile id
	 *
	 * @param int $newProfileId
	 * @throws InvalidArgumentException if $profileId is not integer
	 * @throws RangeException if $newProfileId is not positive
	 */
	public function setProfileId($newProfileId) {
		// base case: if the profile id is null, this a new profile id without a mySQL assigned id (yet)
		if($newProfileId === null) {
			$this->profileId = $ProfileId = null;
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
	 * @return mixed
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

			//convert and store user id
			$this->userId = intval($newUserId);
		}
	}


	/**
	 * accessor method for email
	 *
	 * @return int value of email
	 */

	public function getEmail() {
		return ($this->email);
	}

	/**
	 * mutator method for email
	 *
	 * @param int $newEmail new value of email
	 * @throws InvalidArgumentException if $newEmail is not a integer or not positive
	 * @throws RangeException if $newEmail is not Positive
	 */

	public function setEmail($newEmail) {
		// verify if email is positive
		if($newEmail = filter_var($newEmail, FILTER_VALIDATE_EMAIL)) ;
		if($newEmail === false) {
			throw(new InvalidArgumentException ("email is not a valid email"));
		}

		//verify the email is positive
		if($newEmail <= 0) {
			throw(new RangeException("email is not positive"));
		}

		// convert and store email id
		$this->profileId = intval($newEmail);
	}


	/**
	 * insert this profile id into mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL relates errors occur
	 *
	 **/
	public function insert(PDO &$pdo) {
		// enforce the profileId is null (i.e. don't insert if a profile id already exist)
		if($this->profileId !== null) {
			throw(new PDOException("not a new profile id"));
		}

		// create a query template
		$query = "INSERT INTO profile(profileId, userId, email) VALUES(:proflieId, :uerId, :email)";
		$statement = $pdo->prepare($query);

		// update the null profileId with what mySQL gave us
		$this->profileId = intval($pdo->lastInsertId());

		// bind profileId to placeholders in template
		$parameters = array("profileId" => $this->getProfileId(), "userId" =>$this->getUserId(), "email" =>$this->getEmail());
		$statement->execute($parameters);

		// Update the null profile ID with what MySQL has generate
		$this->setProfileId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this restaurant from MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this restaurant already exists
		if($this->getProfileId() === null) {
			throw(new PDOException("Unable to delete a restaurant that does not exist"));
		}

	// Create query template
	$query = "DELETE FROM profile WHERE profileId = :profileId";
	$statement = $pdo->prepare($query);

	// Bind the member variables to the place holders in the template
	$parameters = array("profileId" => $this->getProileId());
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
		$query = "UPDATE profile SET profileId = :profileId, userId = :userId, email = :email, name = :name WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("profileId" => $this->getProfileId(), "userId" =>$this->getUserId(), "email" =>$this->getEmail());
		$statement->execute($parameters);
	}

	/**
	 * Gets the profile by profile ID
	 *
	 * @param PDO $pd pointer to the PDO connection, by reference
	 * @param int $profileId profile ID to search for
	 * @return mixed profile found null if not found
	 * @throws PDOException when MySQL related errors
	 */
	public static function getProfileByProfileId(PDO &$pdo, $profileId) {
		// sanitize the tweetId before searching
		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);
		if($profileId === false) {
			throw(new PDOException("profile id is not an integer"));
		}
		if($profileId <= 0) {
			throw(new PDOException("profile id is not positive"));
		}

		// create query template
		$query	 = "SELECT profileId, userId, email FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the tweet id to the place holder in the template
		$parameters = array("tweetId" => $profileId);
		$statement->execute($parameters);

		// grab the tweet from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row   = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["userId"], $row["email"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($profile);
	}
	/**
	 * Gets the profile by userId
	 *
	 * @param PDO $pod pointer to the PDO connection, by reference
	 * @param int $userId profile ID to search for
	 * @return mixed profile found null if not found
	 * @throws PDOException when MySQL related errors
	 */
	public static function getProfileByuserId(PDO &$pdo, $userId) {
		// sanitize the tweetId before searching
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

		// bind the tweet id to the place holder in the template
		$parameters = array("tweetId" => $userId);
		$statement->execute($parameters);

		// grab the tweet from mySQL
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
	 * Gets the profile by email ID
	 *
	 * @param PDO $pd pointer to the PDO connection, by reference
	 * @param int $email ID to search for
	 * @return mixed profile found null if not found
	 * @throws PDOException when MySQL related errors
	 */
	public static function getProfileByEmail(PDO &$pdo, $email) {
		// sanitize the tweetId before searching
		$email = filter_var($email, FILTER_VALIDATE_INT);
		if($email === false) {
			throw(new PDOException("tweet id is not an integer"));
		}
		if($email <= 0) {
			throw(new PDOException("tweet id is not positive"));
		}

		// create query template
		$query = "SELECT profileId, userId, email FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the tweet id to the place holder in the template
		$parameters = array("tweetId" => $email);
		$statement->execute($parameters);

		// grab the tweet from mySQL
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
	 * Gets all profile
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @return SplFixedArray of Restaurants found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getAllProfile(PDO &$pdo) {
		// Create query template
		$query = "SELECT profileId, userId, email,";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Build an array of restaurants
		$profile = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				// new Restaurant($restaurantId, $googleId, $facilityKey, $name, $address, $phone, $forkRating)
				$profile = new Profile($row["profileId"], $row["userId"], $row["email"]);
				$profile[$profile->key()] = $profile;
				$profile->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($profile);
	}

}


