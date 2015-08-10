<?php

/**
 * Creation of voted comment class for trufork
 *
 * This script is designed to create voted comments for trufork.
 *
 * @author Trevor Rigler <tarigler@gmail.com>
 *
 **/
class votedComment {
/** above is our class
 *  it's a weak entity
 * it has no primary key
 * it uses profileId and commentId as foreign keys
  * @var int votedComment
 **/


/** below is one of two foreign keys for this class
 * it's the ID of the comment that was voted
 * @var int commentId
 **/
	private $commentId;

/** below is two of two foreign keys for this class
 * it's the ID of the profile that voted on the comment
 * @var int profileId
 **/
	private $profileId;

	/**@var int $voteType
 * below is the sole state variable for class votedComment
 * voteType assigned to comment
 **/
	private $voteType;

/** constructor method for votedComment
 * @param int $votedComment
 * @throws InvalidArgumentException if the data is invalid
 * @throws RangeException if data out of range
 * @throws Exception For all other cases
 *
 **/
public function __construct($commentId, $profileId, $votedCommentVoteType = null) {
	try {
		$this->setCommentId($commentId);
		$this->setProfileId($profileId);
		$this->setVoteType($votedCommentVoteType);
	}	catch(InvalidArgumentException $invalidArgument) {
		 throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
	}	catch(RangeException $range) {
		 throw(new RangeException($range->getMessage(), 0, $range));
	}	catch(Exception $exception) {
		 throw(new Exception($exception->getMessage(), 0, $exception));
		}

}
	/** accessor method for commentId
	 * @return int
	 **/
	public function getCommentId() {
		return $this->commentId;
	}

	/** mutator method for commentId
	 * @param int $commentId
	 **/
	public function setCommentId($newCommentId) {
		if($newCommentId === null) {
			$this->commentId = null;
			return;
		}
		$newCommentId = filter_var($newCommentId, FILTER_VALIDATE_INT);
		if($newCommentId === false) {
			throw(new InvalidArgumentException("voted comment comment ID not a valid integer"));
		}
		if($newCommentId <= 0) {
			throw(new RangeException("voted comment comment Id is not positive"));
		}
		$this->commentId = intval($newCommentId);
	}

	/** accessor method for profileId
	 * @return int
	 **/
	public function getProfileId() {
		return $this->profileId;
	}

	/** mutator method for profileId
	 * @param int $profileId
	 **/
	public function setProfileId($newProfileId) {
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("voted comment profile ID not a valid integer"));
		}
		if($newProfileId <= 0) {
			throw(new RangeException("voted comment profile Id is not positive"));
		}
		$this->profileId = intval($newProfileId);
	}

	/** accessor method for votedCommentVoteType
	 * @return int
	 **/
	public function getVoteType() {
		return $this->voteType;
	}

	/** mutator method for votedCommentVoteType
	 * @param int $voteType
	 */
	public function setVoteType($newVoteType) {
		if($newVoteType === null) {
			$this->voteType = null;
			return;
		}
		$newVoteType = filter_var($newVoteType, FILTER_VALIDATE_INT);
		if($newVoteType === false) {
			throw(new InvalidArgumentException("voted comment vote type not a valid integer"));
		}
		if($newVoteType <= 0) {
			throw(new RangeException("voted comment vote type is not positive"));
		}
		$this->voteType = intval($newVoteType);
	}


	/**
	 * Inserts this voted comment into MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function insert(PDO &$pdo) {
		// Make sure this is a new voted comment
		if($this->commentId !== null) {
			throw(new PDOException("Not a new voted comment"));
		}

		// Create query template
		$query = "INSERT INTO votedComment(commentId, profileId, voteType) VALUES(:commentID, :profileId, :voteType)";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the template
		$parameters = array("commentId" => $this->getCommentId(), "profileId" => $this->getProfileId());
		$statement->execute($parameters);

		// Update the null comment ID with what MySQL has generated
		$this->setCommentId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this voted comment from MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this voted comment already exists
		if($this->getCommentId() === null) {
			throw(new PDOException("Unable to delete a voted comment that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM votedComment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("commentId" => $this->getCommentId());
		$statement->execute($parameters);
	}

	/**
	 * Updates this voted comment in MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function update(PDO &$pdo) {
		// Make sure this voted comment exists
		if($this->getCommentId() === null) {
			throw(new PDOException("Unable to update a comment that does not exist"));
		}

		// Create query template
		$query = "UPDATE votedComment SET commentId = :commentId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("profileId" => $this->getProfileId(), "commentId" => $this->getCommentId());
		$statement->execute($parameters);
	}

	/** gets the voted comment by commentId
	 * @param PDO $pdo pointer to PDO connection by reference
	 * @param int $commentId voted comment comment to search for
	 * @return mixed comment found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getVotedCommentByCommentId(PDO &$pdo, $CommentId) {
		$CommentId = trim($CommentId);
		$CommentId = filter_var($CommentId, FILTER_VALIDATE_INT);
		if(empty($CommentId) === true) {
			throw(new PDOException("voted comment id is not a valid integer"));
		}
		$query = "SELECT commentId, profileId, voteType FROM votedComment WHERE voteType = :voteType";
		$statement = $pdo->prepare($query);

		$commentId = "$CommentId";
		$parameters = array("commentId" => $commentId);
		$statement->execute($parameters);

		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new comment($row["commentId"], $row["profileId"], $row["votedCommentVoteType"], $row["commentDateTime"]);
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

	/** gets the voted comment by profileId
	 * @param PDO $pdo pointer to PDO connection by reference
	 * @param int $profileId voted comment profileId to search for
	 * @return mixed profile found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getVotedCommentByProfileId(PDO &$pdo, $ProfileId) {
		$ProfileId = trim($ProfileId);
		$ProfileId = filter_var($ProfileId, FILTER_VALIDATE_INT);
		if(empty($ProfileId) === true) {
			throw(new PDOException("profile id is not a valid integer"));
		}
		$query = "SELECT commentId, profileId, voteType FROM votedComment WHERE voteType = :voteType";
		$statement = $pdo->prepare($query);

		$profileId = "$ProfileId";
		$parameters = array("profileId" => $profileId);
		$statement->execute($parameters);

		$profiles = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new profile($row["commentId"], $row["profileId"], $row["votedCommentVoteType"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		/** count the results in the array and return:
		 *  1) null if 0 results
		 *  2) the entire array if >+ 1 result **/
		$numberOfProfiles = count($profiles);
		if($numberOfProfiles === 0) {
			return (null);
		} else {
			return($profiles);
		}
	}


/** gets the voted comment by vote type
 *
 * @param PDO $pdo pointer to PDO connection by reference
 * @param int $voteType voted comment vote type to search for
 * @return mixed voted comment found or null if not found
 * @throws PDOException when mySQL related errors occur
 **/
	public static function getVotedCommentByVoteType(PDO &$pdo, $votedCommentVoteType) {
		$votedCommentVoteType = filter_var($votedCommentVoteType, FILTER_VALIDATE_INT);
		if($votedCommentVoteType === false) {
			throw(new PDOException("voted comment vote type not valid"));
		}
		$query = "SELECT voteType FROM votedComment WHERE voteType = :voteType";
		$statement = $pdo->prepare($query);

		$parameters = array("votedCommentVoteType" => $votedCommentVoteType);
		$statement->execute($parameters);

		try {
			$votedComment = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$votedComment = new VotedComment($row["commentId"], $row["profileId"], $row["voteType"]);
			}
		}
		catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($votedComment);
	}

/** gets all voted comments
 *
 * @param PDO $pdo pointer to PDO connection, by reference
 * @return mixed SplFixedArray of voted comments found or null if not found
 * @throws PDOException when mySQL related errors occur
 **/
	public static function getAllVotedComments(PDO &$pdo) {
		$query = "SELECT voteType FROM votedComment";
		$statement = $pdo->prepare($query);
		$statement->execute();

		$votedComments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$votedComment = new VotedComment($row["commentId"], $row["profileId"], $row["votedCommentVoteType"]);
				$votedComments[$votedComments->key()] = $votedComment;
				$votedComments->next();
			}catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		/** count results in array and return
		 * 1) null if 0 results
		 * 2) the entire array >= 1 result
		 **/
		$numberOfVotedComments = count($votedComments);
		if($numberOfVotedComments === 0) {
			return (null);
		} else {
			return ($votedComments);
		}
	}
	}
