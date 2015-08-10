<?php
// grab the project test parameters
require_once("trufork.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/votedcomment.php");
require_once(dirname(__DIR__) . "/php/classes/comment.php");
require_once(dirname(__DIR__) . "/php/classes/profile.php");

/**
 * Full PHPUnit test for the votedComment class
 *
 * I am very doubtful that this works.
 *
 * @see Comment
 * @author
 **/
class VotedCommentTest extends TruForkTest {

	/**
	 * valid voteType to use
	 * @var int voteType
	 **/
	protected $VALID_VOTETYPE = 3;

	/**
	 * test inserting a valid votedComment and verify that the actual mySQL data matches
	 **/
	public function testInsertValidVotedComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("votedComment");

		// create a new votedComment and insert to into mySQL
		$votedComment = new votedComment(null, $this->VALID_VOTETYPE);
		$votedComment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoVotedComment = VotedComment::getVotedCommentByCommentId($this->getPDO(), $votedComment->getVotedCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("votedComment"));
		$this->assertSame($pdoVotedComment->getVoteType(), $this->VALID_VOTETYPE);
	}

	/**
	 * test inserting a votedComment that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidVotedComment() {
		// create a comment with a non null commentId and watch it fail
		$votedComment = new VotedComment(TruforkTest::INVALID_KEY, $this->VALID_VOTETYPE);
		$votedComment->insert($this->getPDO());
	}

	/**
	 * test inserting a votedComment, editing it, and then updating it
	 **/
	public function testUpdateValidVotedComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("votedComment");

		// create a new votedComment and insert to into mySQL
		$votedComment = new votedComment(null, $this->VALID_VOTETYPE);
		$votedComment->insert($this->getPDO());

		// edit the votedComment and update it in mySQL
		$votedComment->setVoteType($this->VALID_VOTETYPE);
		$votedComment->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoVotedComment = VotedComment::getVotedCommentByCommentId($this->getPDO(), $votedComment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("votedComment"));
		$this->assertSame($pdoVotedComment->getVoteType(), $this->VALID_VOTETYPE);

	}

	/**
	 * test updating a votedComment that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidVotedComment() {
		// create a voted Comment and try to update it without actually inserting it
		$votedComment = new votedComment(null, $this->VALID_VOTETYPE);
		$votedComment->update($this->getPDO());
	}

	/**
	 * test creating a voted Comment and then deleting it
	 **/
	public function testDeleteValidVotedComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("votedComment");

		// create a new voted Comment and insert to into mySQL
		$votedComment = new votedComment(null, $this->VALID_VOTETYPE);
		$votedComment->insert($this->getPDO());

		// delete the voted Comment from mySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("votedComment"));
		$votedComment->delete($this->getPDO());

		// grab the data from mySQL and enforce the voted Comment does not exist
		$pdoVotedComment = VotedComment::getVotedCommentByCommentId($this->getPDO(), $votedComment->getCommentId());
		$this->assertNull($pdoVotedComment);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("votedComment"));
	}

	/**
	 * test deleting a voted Comment that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidVotedComment() {
		// create a Comment and try to delete it without actually inserting it
		$votedComment = new VotedComment(null, $this->VALID_VOTETYPE);
		$votedComment->delete($this->getPDO());
	}

	/**
	 * test inserting a voted Comment and regrabbing it from mySQL
	 **/
	public function testGetValidVotedCommentByCommentId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("votedComment");

		// create a new voted Comment and insert to into mySQL
		$votedComment = new VotedComment(null, $this->VALID_VOTETYPE);
		$votedComment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoVotedComment = VotedComment::getVotedCommentByCommentId($this->getPDO(), $votedComment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("votedComment"));
		$this->assertSame($pdoVotedComment->getDateTime(), $this->VOTETYPE);
	}

	/**
	 * test grabbing a voted Comment that does not exist

	public function testGetInvalidVotedCommentByCommentId() {
		// grab a comment id that exceeds the maximum allowable comment id
		$comment = Comment::getInvalidVotedCommentByCommentId($this->getPDO(), TruForkTest::INVALID_KEY);
		$this->assertNull($comment);
	} **/
}
