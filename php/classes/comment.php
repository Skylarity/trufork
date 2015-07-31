<?php

/**
 * Creation of Comment Profile for trufork
 *
 * This script is designed to create a basic comment profile for trufork.
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
	/** @var int $dateTime
	 * date time assigned to comment
	 **/
	private $dateTime;
	/** @var int content
	 * content of comment
	 **/
	private $content;

	/** constructor method for Comment
	 * @param int $commentId or null if a new Comment
	 * @param int $dateTimeId datetimestamp of comment
	 * @param int $content content of Comment
	 * @throws InvalidArgumentException if the data is invalid
	 * @throws RangeException if data out of range
	 * @throws Exception For all other cases
	 *
	 **/
	public function __construct($commentId, $commentDateTime, $commentContent = null) {
		try {
			$this->setCommentId($commentId);
			$this->setDateTime($commentDateTime);
			$this->setContent($commentContent);
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

	/**accessor method for getting dateTime
	 * @return int
	 */
	public function getDateTime() {
		return $this->dateTime;
	}


	/**mutator method for setting dateTime
	 * @param int $dateTime
	 */
	public function setDateTime($newDateTime) {
		if ($newDateTime === null) {
			$this->dateTime = null;
			return;
		}
		$newDateTime = filter_var($newDateTime, FILTER_VALIDATE_INT);
		if($newDateTime === false) {
			throw(new InvalidArgumentException("date time is not a valid integer"));
		}
		if($newDateTime <= 0) {
			throw(new RangeException("date time is not positive"));
		}
		$this->dateTime = intval($newDateTime);
	}

	/**accessor method for getting content
	 * @return int
	 */
	public function getContent() {
		return $this->content;
	}

	/**mutator method for setting content
	 * @param int $newContent new value of comment content
	 * @throws RangeException if $newCommentContent is too long
	 * @throws InvalidArgumentException if $newCommentContent fails sanitization
	 */
	public function setContent($newCommentContent) {
		if($newCommentContent === null) {
			$this->commentContent = null;
			return;

		}
		$newCommentContent = filter_var($newCommentContent, FILTER_SANITIZE_STRING);
		if($newCommentContent === false) {
			throw(new InvalidArgumentException("comment content is not valid"));

		}
		if($newCommentContent > 1064) {
			throw(new RangeException("comment content too long"));
		}
		$this->commentContent = intval($newCommentContent);
	}
/**
 * Inserts comment into DB
 *
 * @param PDO $pdo pointer to PDO connection , by reference
 * @throws PDOException when MySQL related errors occur
 **/
	public function insert (PDO &$pdo) {
		if($this->commentId !== null) {
			throw(new PDOException("not a new comment"));
		}
		$query = "INSERT INTO comment(dateTime, content) VALUES(:dateTime, :content)";
		$statement = $pdo->prepare($query);

		$parameters = array("dateTime" => $this->getDateTime(), "comment" => $this->getContent());
		$statement->execute($parameters);

		$this->setCommentId(intval($pdo->lastInsertId()));
	}
	/**
	 * Deletes comment from DB
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 **/
	public function delete (PDO &$pdo) {
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
	public function update (PDO &$pdo) {
		if ($this->getCommentId() === null) {
			throw(new PDOException("cannot update a comment that does not exist"));
		}
		$query = "UPDATE comment SET dateTime = :dateTime, content = :content";
		$statement = $pdo->prepare($query);

		$parameters = array("dateTime" => $this->getDateTime(), "content" => $this->getContent());
		$statement->execute($parameters);
	}

/** gets the comment by comment Id
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
		$query = "SELECT commentId, dateTime, content FROM comment WHERE dateTime = :commentDateTime";
		$statement = $pdo->prepare($query);

		$commentDateTime = "$commentDateTime";
		$parameters = array("commentDateTime" => $commentDateTime);
		$statement->execute($parameters);

		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentDateTime"], $row["commentContent"]);
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
			return($comments);
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
		$query = "SELECT commentId, dateTime, content FROM comment WHERE content = :commentContent";
		$statement = $pdo->prepare($query);

		$parameters = array("commentContent" => $commentContent);
		$statement->execute($parameters);

		try {
			$comment = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$comment = new Comment($row["dateTime"], $row["commentId"], $row["commentContent"]);
			}
		}
		catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
			return($comment);
		}
	/** gets all Comments
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @return mixed SplFixedArray of Comments found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAllComments(PDO &$pdo) {
		$query = "SELECT commentId, dateTime, content FROM comment";
		$statement = $pdo->prepare($query);
		$statement->execute();

		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentDateTime"], $row["commentContent"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			}catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		/** count results in array and return
		 * 1) null if 0 results
		 * 2) the entire array >= 1 result
		 **/
		$numberOfComments = count($comments);
		if($numberOfComments === 0) {
			return (null);
		} else {
			return ($comments);
		}
		}
	}