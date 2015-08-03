<?php

/**
 * Creation of liked restaurant class for trufork
 *
 * This script is designed to create liked restaurant for trufork.
 *
 * @author  Humberto Perez <hperezperez@cnm,edu>
 *
 **/
class LikedRestaurant {
	/** above is our class
	 *  it's a weak entity
	 * it has no primary key
	 * it uses profileId and restaurantId as foreign keys
	 **/
	private $profileId;
	private $restauranId;

	/** constructor method for likeRestaurant
	 * @param int $likeRestaurant
	 * @throws InvalidArgumentException if the data is invalid
	 * @throws RangeException if data out of range
	 * @throws Exception For all other cases
	 *
	 **/
	public function __construct($profileId, $restaurantIdl) {
		try {
			$this->setProfileId($profiledId);
			$this->setRestaurantId($restaurantId);
		}	catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}	catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}	catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}

	}
	/** accessor method for profileId
	 * @return int
	 **/
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
		if($newProfiledId <= 0) {
			throw(new RangeException("profile id is not positive"));
		}
		$this->profileId = intval($newProfileId);
	}


	/** accessor method for restaurantId
	 * @return int
	 */
	public function getrestaurantId() {
		return $this->restarauntId;
	}

	/** mutator method for restaurantId
	 * @param int $restaurantId
	 */
	public function setRestauarantId($) {
		if($newrestauraantidType === null) {
			$this->restaurantIdVoteType = null;
			return;
		}
		$newVotedCommentVoteType = filter_var($newVotedCommentVoteType, FILTER_VALIDATE_INT);
		if($newVotedCommentVoteType === false) {
			throw(new InvalidArgumentException("voted comment vote type not a valid integer"));
		}
		if($newVotedCommentVoteType <= 0) {
			throw(new RangeException("voted comment vote type is not positive"));
		}
		$this->votedCommentVoteType = intval($newVotedCommentVoteType);
	}
	/** inserts voted comment into DB
	 *@param PDO $pdo pointer to pdo connection by reference
	 *@throws PDOException in the event of mySQL errors
	 **/
	public function insert (PDO &$pdo) {
		if($this->votedCommentId !== null) {
			throw(new PDOException("not a new voted comment"));
		}
		$query = "INSERT INTO votedComment(voteType) VALUES(:voteType)";
		$statement = $pdo->prepare($query);

		$parameters = array("voteType" => $this->getVoteType());
		$statement->execute($parameters);

		$this->setVotedCommentId(intval($pdo->lastInsertId()));
	}
	/**
	 * Deletes voted comment from DB
	 *
	 * @param PDO $pdo pointer to PDO connection by reference
	 * @throws PDOException when MySQL related errors occur
	 **/
	public function delete (PDO &$pdo) {
		if($this->votedCommentId === null) {
			throw(new PDOException("cannot delete a voted comment that does not exist"));
		}
		$query = "DELETE FROM comment WHERE votedCommentId = :votedCommentId";
		$statement = $pdo->prepare($query);

		$parameters = array("votedCommentId" => $this->getVotedCommentId());
		$statement->execute($parameters);
	}
	/** updates voted comment in DB
	 *
	 * @param PDO $pdo pointer to PDO connection by reference
	 * @throws PDOException when MySQL related errors occur
	 **/
	public function update (PDO &$pdo) {
		if ($this->getVotedCommentId() === null) {
			throw(new PDOException("cannot update a voted comment that does not exist"));
		}
		$query = "UPDATE comment SET dateTime = :dateTime, content = :content";
		$statement = $pdo->prepare($query);

		$parameters = array("voteType" => $this->getVoteType());
		$statement->execute($parameters);
	}

	/** gets the voted comment by voted comment id
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param $votedCommentId voted comment Id to search for
	 * @return mixed special fixed array of comments found or null if not found
	 * @throws PDOException when MySQL errors happen
	 **/
	public static function getCommentByVotedCommentId(PDO &$pdo, $votedCommentId) {
		$votedCommentId = trim($votedCommentId);
		$votedCommentId = filter_var($votedCommentId, FILTER_VALIDATE_INT);
		if(empty($votedCommentId) === true) {
			throw(new PDOException("voted comment id is not a valid integer"));
		}
		$query = "SELECT votedCommentId, voteType FROM votedComment WHERE voteType = :voteType";
		$statement = $pdo->prepare($query);

		$votedCommentId = "$votedCommentId";
		$parameters = array("votedCommentId" => $votedCommentId);
		$statement->execute($parameters);

		$votedComments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$votedComment = new votedComment($row["votedCommentId"], $row["votedCommentVoteType"]);
				$votedComments[$votedComments->key()] = $votedComment;
				$votedComments->next();
			} catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		/** count the results in the array and return:
		 *  1) null if 0 results
		 *  2) the entire array if >+ 1 result **/
		$numberOfVotedComments = count($votedComments);
		if($numberOfVotedComments === 0) {
			return (null);
		} else {
			return($votedComments);
		}
	}

	/** gets the voted comment by vote type
	 *
	 * @param PDO $pdo pointer to PDO connection by reference
	 * @param int $votedCommentVoteType voted comment vote type to search for
	 * @return mixed voted comment found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getVotedCommentByVoteType(PDO &$pdo, $votedCommentVoteType) {
		$votedCommentVoteType = filter_var($votedCommentVoteType, FILTER_VALIDATE_INT);
		if($votedCommentVoteType === false) {
			throw(new PDOException("voted comment vote type not valid"));
		}
		$query = "SELECT votedCommentId, voteType FROM votedComment WHERE voteType = :voteType";
		$statement = $pdo->prepare($query);

		$parameters = array("votedCommentVoteType" => $votedCommentVoteType);
		$statement->execute($parameters);

		try {
			$votedComment = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$votedComment = new VotedComment($row["voteType"], $row["votedCommentId"]);
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
		$query = "SELECT votedCommentId, voteType FROM votedComment";
		$statement = $pdo->prepare($query);
		$statement->execute();

		$votedComments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$votedComment = new VotedComment($row["votedCommentId"], $row["votedCommentVoteType"]);
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







