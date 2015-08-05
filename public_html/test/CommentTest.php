<?php
// grab the project test parameters
require_once("trufork.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/comment.php");


/**
 * Full PHPUnit test for the Comment class
 *
 * This is a complete PHPUnit test of the Comment class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Comment
 * @author
 **/
class CommentTest extends TruForkTest {
	/**
	 * valid at handle to use
	 * @var ???
	 **/
	protected $VALID_DATETIME = "????";
	/**
	 * valid content to use
	 * @var ???
	 **/
	protected $VALID_CONTENT = "????";

	/**
	 * test inserting a valid Comment and verify that the actual mySQL data matches
	 **/
	public function testInsertValidComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test inserting a Comment that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidComment() {
		// create a comment with a non null commentId and watch it fail
		$comment = new Comment(TruforkTest::INVALID_KEY, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());
	}

	/**
	 * test inserting a Comment, editing it, and then updating it
	 **/
	public function testUpdateValidComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// edit the Comment and update it in mySQL
		$comment->setDateTime($this->VALID_DATETIME);
		$comment->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test updating a Comment that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidComment() {
		// create a Comment and try to update it without actually inserting it
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->update($this->getPDO());
	}

	/**
	 * test creating a Comment and then deleting it
	 **/
	public function testDeleteValidComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// delete the Comment from mySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$comment->delete($this->getPDO());

		// grab the data from mySQL and enforce the Comment does not exist
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertNull($pdoComment);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("comment"));
	}

	/**
	 * test deleting a Comment that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidComment() {
		// create a Comment and try to delete it without actually inserting it
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->delete($this->getPDO());
	}

	/**
	 * test inserting a Comment and regrabbing it from mySQL
	 **/
	public function testGetValidCommentByCommentId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test grabbing a Comment that does not exist
	 **/
	public function testGetInvalidCommentByCommentId() {
		// grab a comment id that exceeds the maximum allowable comment id
		$comment = Comment::getCommentByCommentId($this->getPDO(), TruForkTest::INVALID_KEY);
		$this->assertNull($comment);
	}

	public function testGetValidCommentByDateTime() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByDateTime($this->getPDO(), $this->VALID_DATETIME);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test grabbing a Comment by date time that does not exist
	 **/
	public function testGetInvalidCommentByDateTime() {
		// grab a date time that does not exist
		$comment = Comment::getCommentByDateTime($this->getPDO(), "@doesnotexist");
		$this->assertNull($comment);
	}

	/**
	 * test grabbing a Comment by content
	 **/
	public function testGetValidCommentByContent() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Profile and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByContent($this->getPDO(), $this->VALID_CONTENT);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test grabbing a Comment by content that does not exists
	 **/
	public function testGetInvalidCommentByContent() {
		// grab content that does not exist
		$comment = Comment::getCommentByContent($this->getPDO(), "????");
		$this->assertNull($comment);
	}
}