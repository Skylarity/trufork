<?php

/**
 * User Class for trufork.com. This class sets User Id and Salt and Hash
 *
 * @author Kenneth Chavez <kchavez68@gmail.com>
 */
class User {
	/**
	 * id for this user is a primary key
	 * @var int $userId
	**/
	private $userId;
	/**
	 * salt encryption for userId
	 * @var string $salt
	 **/
	private $salt;
	/**
	 * hash encryption for userId
	 * @var string $hash password
	 * */
	private $hash;




	/**
	 * Constructor method for this User
	 *
	 * @param int $newUserId of this user or null if new user
	 * @param string $salt sets salt
	 * @param string $hash sets hash
	 * @throw InvalidArgumentException if data types are not valid
	 * @throws Exception if some other exception is thrown
	 */
	public function __construct($newUserId, $salt, $hash) {
		try {
			$this->setUserId($newUserId);
			$this->setSalt($salt);
			$this->sethash($hash);
		} catch(InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the call
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to caller
			throw(new RangeException($range->getMessage(), 0, $range));
			// rethrow the exception to caller
		} catch(Exception $e) {
			//rethrow generic exception
			throw(new Exception($e->getMessage(), 0, $e));
		}
	}

		/**
	 *accessor method for user id
	 *
	 *@return int value for new user id
	 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * mutator method for user id
	 *
	 * @param mixed $newUserId or null for new $newUserId
	 * @throws InvalidArgumentException if $newUser Id in not a vaild Interger
	 * @throws InvalidArgumentException if User Id is not Positive
	 */

	public function setUserId($newUserId) {
		//base: case: if the user id is null, this a new user id without mySQL assigned id (yet)
		if($newUserId === null) {
			$this->userId = null;
			return;
		}
		// verify the the user id is valid
		$newUserId = filter_var($newUserId, FILTER_VALIDATE_INT);
		if($newUserId === false) {
			throw(new InvalidArgumentException("user id is not a valid integer"));
		}
		// verify the profile id is positive
		if($newUserId <= 0) {
			throw(new InvalidArgumentException("user id is not positive"));
			// covert and store the profile id
		}
			$this->userId = intval($newUserId);
		}

	/** accessor method for salt
	 * @return mixed
	 *
	 */
	public function getSalt() {
		return $this->salt;
	}
	// base case: if the salt id is null, this is a new salt without a mySQL assigned id

	/**
	 * mutator method for salt
	 *
	 * @param string $newSalt with a hex of 64
	 * @throws RangeException if salt is not Valid
	 */
	public function setSalt($newSalt) {
		// verify salt is exactly string of 64
		if((ctype_xdigit($newSalt)) === false) {
			if(empty($newSalt) === true) {
				throw new InvalidArgumentException ("salt invalid");
			}
			if(strlen($newSalt) !== 64) {
				throw (new RangeException ("salt not valid"));
			}
		}
		$this->salt = $newSalt;
	}

	/**
	 * accessor method for Hash
	 *
	 * @return string hash
	 */
	public function getHash() {
		return $this->hash;
	}


	/**
	 * mutator method for hash
	 *
	 * @param string $newHash with cytpe xdigit with a string of 128
	 * @throws RangeException if has is not 128
	 */
	public function setHash($newHash) {
		// verify Hash is exactly string of 128
		if((ctype_xdigit($newHash)) === false) {
			if(empty($newHash) === true) {
				throw new InvalidArgumentException ("hash invalid");
			}
			if(strlen($newHash) !== 128) {
				throw new RangeException ("hash not valid");
			}
		}
		$this->hash = $newHash;
	}

	/**
	 * insert this userId into mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL relates errors occur
	 *
	 **/
	public function insert(PDO &$pdo) {
		// enforce the profileId is null (i.e. don't insert if a profile id already exist)
		if($this->userId !== null) {
			throw(new PDOException("not a new user id"));
		}

		// create a query template 
		$query = "INSERT INTO user(salt, hash) VALUES(:salt, :hash)";
		$statement = $pdo->prepare($query);

		// bind profileId to placeholders in template
		$parameters = array("salt" =>$this->getSalt(),"hash" =>$this->getHash());
		$statement->execute($parameters);

		// Update the null profile ID with what MySQL has generate
		$this->setUserId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this User from MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this restaurant already exists
		if($this->getUserId() === null) {
			throw(new PDOException("Unable to delete a user that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM user WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the place holders in the template
		$parameters = array("userId" => $this->getUserId());
		$statement->execute($parameters);
	}

	/**
	 * Updates this userId in MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function update(PDO &$pdo) {
		// Make sure this restaurant exists
		if($this->getUserId() === null) {
			throw(new PDOException("Unable to update a user that does not exist"));
		}

		// Create query template
		$query = "UPDATE user SET userId = :userId, salt = :salt, hash = :hash WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("userId" => $this->getUserId(), "salt" =>$this->getSalt(), "hash" =>$this->gethash());
		$statement->execute($parameters);
	}
	/**
	 * Gets the profile by user ID
	 *
	 * @param PDO $pdo pointer to the PDO connection, by reference
	 * @param int $user user ID to search for
	 * @return mixed profile found null if not found
	 */
	public static function getUserByUserId(PDO &$pdo, $user) {
		// sanitize the tweetId before searching
		$user = filter_var($user, FILTER_VALIDATE_INT);
		if($user === false) {
			throw(new PDOException("user id is not an integer"));
		}
		if($user <= 0) {
			throw(new PDOException("user id is not positive"));
		}

		// create query template
		$query	 = "SELECT userId, salt, hash FROM user WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		// bind the user id to the place holder in the template
		$parameters = array("userId" => $user);
		$statement->execute($parameters);

		// grab the tweet from mySQL
		try {
			$user = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row   = $statement->fetch();
			if($row !== false) {
				$user = new User($row["userId"], $row["salt"], $row["hash"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($user);
	}
}



