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
	public
	function setDateTime($dateTime) {
		$this->dateTime = $dateTime;
	}

	/**accessor method for getting content
	 * @return int
	 */
	public
	function getContent() {
		return $this->content;
	}

	/**mutator method for setting content
	 * @param int $newContent new value of comment content
	 * @throws RangeException if $newCommentContent is too long
	 * @throws InvalidArgumentException if $newCommentContent fails sanitization
	 */
	public
	function setContent($newCommentContent) {
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


}


/** TO DO
 * finish mutator methods,
 * finish set methods,
 * finish insert methods,
 * finish update methods,
 * finish delete methods **/