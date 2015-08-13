<?php

//grab the date parsing function
require_once(dirname(__DIR__) . "/traits/validatedate.php");
require_once(dirname(__DIR__) . "/helpers/filter.php");

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

	/** @var DateTime $dateTime
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
	 * @param DateTime $dateTime datetimestamp of comment
	 * @param int $content content of Comment
	 * @throws InvalidArgumentException if the data is invalid
	 * @throws RangeException if data out of range
	 * @throws Exception For all other cases
	 *
	 **/
	public function __construct($commentId, $profileId, $restaurantId, $dateTime, $content) {
		try {
			$this->setCommentId($commentId);
			$this->setProfileId($profileId);
			$this->setRestaurantId($restaurantId);
			$this->setDateTime($dateTime);
			$this->setContent($content);
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
		$this->commentId = Filter::filterInt($newCommentId, "Comment ID", true);
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
		$this->profileId = Filter::filterInt($newProfileId, "Profile ID", true);
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
		$this->restaurantId = Filter::filterInt($newRestaurantId, "Restaurant ID", true);
	}


	/**accessor method for getting dateTime
	 * @return DateTime
	 */
	public function getDateTime() {
		return $this->dateTime;
	}


	/**mutator method for setting dateTime
	 * @param mixed $newDateTime
	 * $throws InvalidArgumentException if $commentDateTime is not a valid object or string
	 * $throws RangeException if $commentDateTime is out of range or a non-existent date
	 * uses validateDate (grabs it at beginning of code, references it in state variables
	 */
	public function setDateTime($newDateTime) {
		//if date null, default to current date
		if($newDateTime === null) {
			$this->dateTime = new DateTime();
			return;
		}
		//store comment date
		try {
			$newDateTime = validateDate::validateDate($newDateTime);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
		$this->dateTime = $newDateTime;

	}

	/**accessor method for getting content
	 * @return int
	 */
	public function getcontent() {
		return $this->content;
	}

	/**mutator method for setting content
	 * @param int $newContent new value of comment content
	 * @throws RangeException if $newContent is too long
	 * @throws InvalidArgumentException if $newContent fails sanitization
	 */
	public function setContent($newContent) {
		$this->content = Filter::filterString($newContent, "Comment content", 1064);
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
		$query = "INSERT INTO comment(profileId, restaurantId, dateTime, content) VALUES(:profileId, :restaurantId, :dateTime, :content)";
		$statement = $pdo->prepare($query);

		$parameters = array("profileId" => $this->getProfileId(), "restaurantId" => $this->getRestaurantId(), "dateTime" => $this->getDateTime()->format("Y-m-d H:i:s"), "content" => $this->getcontent());
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

		$parameters = array("dateTime" => $this->getDateTime()->format("Y-m-d H:i:s"), "content" => $this->getcontent());
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
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		$parameters = array("commentId" => $commentId);
		$statement->execute($parameters);

		try {
			$comment = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["dateTime"], $row["content"]);
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
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content FROM comment WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind profile id to placeholder in template
		$parameters = array("profileId" => $profileId);
		$statement->execute($parameters);

		//build array of comments
		$comments = new SplFixedArray($statement->rowCount());

		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["dateTime"], $row["content"]);
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
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content FROM comment WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		//bind restaurant id to placeholder in comments
		$parameters = array("restaurantId" => $restaurantId);
		$statement->execute($parameters);

		//build an array of comments
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["dateTime"], $row["content"]);
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
	 * @param $dateTime comment date to search for
	 * @return mixed special fixed array of comments found or null if not found
	 * @throws PDOException when MySQL errors happen
	 **/
	public static function getCommentByDateTime(PDO &$pdo, $dateTime) {
		// Turn date into string
		if(is_object($dateTime) === true && get_class($dateTime) === "DateTime") {
			$dateTime = validateDate::validateDate($dateTime);
		}
		// Turn date into string
		if(is_object($dateTime) === true && get_class($dateTime) === "DateTime") {
			$dateTime = $dateTime->format("Y-m-d H:i:s");
		}

		if(empty($dateTime) === true) {
			throw(new PDOException("date time is not  valid "));
		}
		$query = "SELECT commentId, profileId, restaurantId, dateTime, content FROM comment WHERE dateTime = :dateTime";
		$statement = $pdo->prepare($query);

		$dateTime = "$dateTime";
		$parameters = array("dateTime" => $dateTime);
		$statement->execute($parameters);

		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["dateTime"], $row["content"]);
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
	 * @param int $content comment content to search for
	 * @return mixed Comment found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getCommentByCommentContent(PDO &$pdo, $content) {
		$content = filter_var($content, FILTER_SANITIZE_STRING);
		if($content === false) {
			throw(new PDOException("comment content not valid"));
		}
		$query = "SELECT * FROM comment WHERE content LIKE :content";
		$statement = $pdo->prepare($query);

		//bind comment content to placeholder in template
		$content = "%$content%";
		$parameters = array("content" => $content);
		$statement->execute($parameters);

		//build an array of comments
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["profileId"], $row["restaurantId"], $row["dateTime"], $row["content"]);
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