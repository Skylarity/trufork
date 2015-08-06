<?php

//grab the date parsing function
require_once(dirname(__DIR__) . "/functions/validatedate.php");

/**
 * Creation of Comment class for trufork
 *
 * This script is designed to create a basic comment class for trufork.
 *
 * @author Trevor Rigler <tarigler@gmail.com>
 *
 **/
class Comment {
	/** above is our class, it's the id of the comment made by user
	 * it's a primary key
	 * @var int $commentId
	 *
	 **/
	private $commentId;

	/** @var  int $profileId
	 * profileId assigned to comment
	 **/
	private $profileId;

	/** @var int restaurantId
	 * restaurantId assigned to comment
	 **/
	private $restaurantId;

	/** @var int $dateTime
	 * date time assigned to comment
	 **/
	private $dateTime;

	/** @var int content
	 * content of comment
	 **/
	private $content;

	use validateDate;

	/** constructor method for Comment
	 * @param int $commentId or null if a new Comment
	 * @param int $profileId or null if a new profileId
	 * @param int $restaurantId or null if a new restaurantId
	 * @param int $dateTime datetimestamp of comment
	 * @param int $content content of Comment
	 * @throws InvalidArgumentException if the data is invalid
	 * @throws RangeException if data out of range
	 * @throws Exception For all other cases
	 *
	 **/
	public function __construct($commentId, $profileId, $restaurantId, $commentDateTime, $commentContent = null) {
		try {
			$this->setCommentId($commentId);
			$this->setProfileId($profileId);
			$this->setRestaurantId($restaurantId);
			$this->setCommentDateTime($commentDateTime);
			$this->setCommentContent($commentContent);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// Rethrow exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			// Rethrow exception to the caller
			/** rethrow generic exception
			 **/
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/** accessor method for getting commentId
	 * @return int
	 */
	public function getCommentId() {
		return $this->commentId;
	}

	/**mutator method for setting commentId
	 * @param int $commentId
	 */
	public function setCommentId($newCommentId) {
		if($newCommentId === null) {
			$this->commentId = null;
			return;
		}
		$newCommentId = filter_var($newCommentId, FILTER_VALIDATE_INT);
		if($newCommentId === false) {
			throw(new InvalidArgumentException("comment id is not a valid integer"));
		}
		if($newCommentId <= 0) {
			throw(new RangeException("comment id is not positive"));
		}
		$this->commentId = intval($newCommentId);
	}

	/** accessor method for getting profileId
	 * @return int
	 */
	public function getProfileId() {
		return $this->profileId;
	}

	/**mutator method for setting profileId
	 * @param int $profileId
	 */
	public function setProfileId($newProfileId) {
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("profile id is not a valid integer"));
		}
		if($newProfileId <= 0) {
			throw(new RangeException("profile id is not positive"));
		}
		$this->profileId = intval($newProfileId);
	}

	/** accessor method for getting restaurantId
	 * @return int
	 */
	public function getRestaurantId() {
		return $this->restaurantId;
	}

	/**mutator method for setting restaurantId
	 * @param int $restaurantId
	 */
	public function setRestaurantId($newRestaurantId) {
		if($newRestaurantId === null) {
			$this->restaurantId = null;
			return;
		}
		$newRestaurantId = filter_var($newRestaurantId, FILTER_VALIDATE_INT);
		if($newRestaurantId === false) {
			throw(new InvalidArgumentException("restaurant id is not a valid integer"));
		}
		if($newRestaurantId <= 0) {
			throw(new RangeException("restaurant id is not positive"));
		}
		$this->restaurantId = intval($newRestaurantId);
	}


	/**accessor method for getting dateTime
	 * @return int
	 */
	public function getDateTime() {
		return $this->dateTime;
	}


	/**mutator method for setting dateTime
	 * @param mixed $commentDateTime
	 * $throws InvalidArgumentException if $commentDateTime is not a valid object or string
	 * $throws RangeException if $commentDateTime is out of range or a non-existent date
	 * uses validateDate (grabs it at beginning of code, references it in state variables
	 */
	public function setCommentDateTime($newCommentDateTime) {
		//if date null, default to current date
		if($newCommentDateTime === null) {
			$this->commentDateTime = null;
			return;
		}
		//store comment date
		try {
			$newCommentDateTime = validateDate::validateDate($newCommentDateTime);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
		$this->commentDateTime = $newCommentDateTime;

	}

	/**accessor method for getting content
	 * @return int
	 */
	public function getCommentContent() {
		return $this->content;
	}

	/**mutator method for setting content
	 * @param int $newContent new value of comment content
	 * @throws RangeException if $newCommentContent is too long
	 * @throws InvalidArgumentException if $newCommentContent fails sanitization
	 */
	public function setCommentContent($newCommentContent) {
		if($newCommentContent === null) {
			$this->content = null;
			return;

		}
		$newCommentContent = filter_var($newCommentContent, FILTER_SANITIZE_STRING);
		if($newCommentContent === false) {
			throw(new InvalidArgumentException("comment content is not valid"));

		}
		if($newCommentContent > 1064) {
			throw(new RangeException("comment content too long"));
		}
		$this->content = intval($newCommentContent);
	}

	/**
	 * Inserts comment into DB
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		if($this->commentId !== null) {
			throw(new PDOException("not a new comment"));
		}
		$query = "INSERT INTO comment(dateTime, content) VALUES(:dateTime, :content)";
		$statement = $pdo->prepare($query);

		$parameters = array("dateTime" => $this->getDateTime(), "content" => $this->getContent());
		$statement->execute($parameters);

		$this->setCommentId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes comment from DB
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 **/
	public function delete(PDO &$pdo) {
		if($this->commentId === null) {
			throw(new PDOException("cannot delete a comment that does not exist"));
		}
		$query = "DELETE FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		$parameters = array("commentId" => $this->getCommentId());
		$statement->execute($parameters);
	}

	/** updates comment in DB
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when MySQL related errors occur
	 **/
	public function update(PDO &$pdo) {
		if($this->getCommentId() === null) {
			throw(new PDOException("cannot update a comment that does not exist"));
		}
		$query = "UPDATE comment SET dateTime = :dateTime, content = :content";
		$statement = $pdo->prepare($query);

		$parameters = array("dateTime" => $this->getDateTime(), "content" => $this->getContent());
		$statement->execute($parameters);
	}

	/**
	 * Gets the comment by comment ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $commentId comment ID to search for
	 * @return mixed Comment found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getCommentByCommentId(PDO &$pdo, $commentId) {

		$commentId = filter_var($commentId, FILTER_VALIDATE_INT);
		if($commentId === false) {
			throw(new PDOException("comment ID is not an integer"));
		}
		if($commentId <= 0) {
			throw(new PDOException("comment ID is not positive"));
		}

		// Create query template
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content, name FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		$parameters = array("commentId" => $commentId);
		$statement->execute($parameters);

		try {
			$comment = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["dateTime"], $row["comment"]);
			}
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		return ($comment);
	}

	/**
	 * Gets the comment by profile ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $profileId profile ID to search for
	 * @return mixed Comment found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getCommentByProfileId(PDO &$pdo, $profileId) {

		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);
		if($profileId === false) {
			throw(new PDOException("profile ID is not an integer"));
		}
		if($profileId <= 0) {
			throw(new PDOException("profile ID is not positive"));
		}

		// Create query template
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content, name FROM comment WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind profile id to placeholder in template
		$parameters = array("profileId" => $profileId);
		$statement->execute($parameters);

		//build array of comments
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["commentDateTime"], $row["commentContent"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(Exception $exception) {
				// if row can't be converted, rethrow
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return ($comments);
	}

	/**
	 * Gets the comment by restaurant ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $restaurantId restaurant ID to search for
	 * @return mixed Comment found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getCommentByRestaurantId(PDO &$pdo, $restaurantId) {

		$restaurantId = filter_var($restaurantId, FILTER_VALIDATE_INT);
		if($restaurantId === false) {
			throw(new PDOException("restaurant ID is not an integer"));
		}
		if($restaurantId <= 0) {
			throw(new PDOException("restaurant ID is not positive"));
		}

		// Create query template
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content, name FROM comment WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		//bind restaurant id to placeholder in comments
		$parameters = array("restaurantId" => $restaurantId);
		$statement->execute($parameters);

		//build an array of comments
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["commentDateTime"], $row["commentContent"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(Exception $exception) {
				//rethrow upon nonconversion of row
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
	}


	/** gets the comment by comment date time
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param $commentId comment Id to search for
	 * @return mixed special fixed array of comments found or null if not found
	 * @throws PDOException when MySQL errors happen
	 **/
	public static function getCommentByCommentDateTime(PDO &$pdo, $commentDateTime) {
		$commentDateTime = trim($commentDateTime);
		$commentDateTime = filter_var($commentDateTime, FILTER_VALIDATE_INT);
		if(empty($commentDateTime) === true) {
			throw(new PDOException("date time is not a valid integer"));
		}
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content FROM comment WHERE dateTime = :commentDateTime";
		$statement = $pdo->prepare($query);

		$commentDateTime = "$commentDateTime";
		$parameters = array("commentDateTime" => $commentDateTime);
		$statement->execute($parameters);

		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["commentDateTime"], $row["commentContent"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		/** count the results in the array and return:
		 *  1) null if 0 results
		 *  2) the entire array if >+ 1 result **/
		$numberOfComments = count($comments);
		if($numberOfComments === 0) {
			return (null);
		} else {
			return ($comments);
		}
	}

	/** gets the comment by comment content
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $commentContent comment content to search for
	 * @return mixed Comment found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getCommentByCommentContent(PDO &$pdo, $commentContent) {
		$commentContent = filter_var($commentContent, FILTER_SANITIZE_STRING);
		if($commentContent === false) {
			throw(new PDOException("comment content not valid"));
		}
		$query = "SELECT commentId, dateTime, content FROM comment WHERE commentContent LIKE :commentContent";
		$statement = $pdo->prepare($query);

		//bind comment content to placeholder in template
		$commentContent = "%$commentContent%";
		$parameters = array("commentContent" => $commentContent);
		$statement->execute($parameters);

		//build an array of comments
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["commentDateTime"], $row["commentContent"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(Exception $exception) {
				//rethrow upon nonconversion of row
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);

	}
}