<?php
// grab the project test parameters
require_once(dirname(__DIR__) . "/test/trufork.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/comment.php");
require_once(dirname(__DIR__) . "/php/classes/restaurant.php");
require_once(dirname(__DIR__) . "/php/classes/profile.php");
require_once(dirname(__DIR__) . "/php/classes/user.php");

/**
 * PHPUnit test for the Comment class
 *
 * ????
 *
 * @see Comment
 * @author
 **/
class CommentTest extends TruForkTest {

	/**
	 * valid at datetime to use
	 * @var int dateTime
	 **/
	protected $VALID_DATETIME = "2015-01-01 15:16:17";

	/**
	 * valid content to use
	 * @var string content
	 **/
	protected $VALID_CONTENT = "bigoldstringofnonsense";

	/**
	 * valid Profile that created the comment
	 * @var Profile $profile
	 **/
	protected $profile = null;

	/**
	 * valid Restaurant upon which the comment is made
	 * @var Restaurant $restaurant
	 **/
	protected $restaurant = null;


	/**
	 * valid User by which comment is made via Profile
	 * @var User $user
	 *
	 **/
protected $user = null;


	/**
	 *
	 * create dependent objects ProfileId & RestaurantId b4 running each test
	 *
	 **/

	public final function setUp() {
		// run the default setup() method first
		parent::setUp();

		//create and insert a User to own the test Comment via Profile
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$hash = hash_pbkdf2("sha512","password1234", $salt, 262144, 128);
		$this->user = new User(null, $salt, $hash);
		$this->user->insert($this->getPDO());

		//create and insert a Profile to own the test Comment
		$this->profile = new Profile(null, $this->user->getUserId(), "joebob@sixfinger.com");
		$this->profile->insert($this->getPDO());


		//create and insert a Restaurant to own the test Comment
		$this->restaurant = new Restaurant(null, "ChIJ18Mh_sa6woARKLIrL9eOxTs", "ABCD12345678", "Sticky Rice", "317 South Broadway, Los Angeles, CA 90013", "+18185551212", "5");
		$this->restaurant->insert($this->getPDO());

		//create the new test Comment
		$this->comment = new Comment($this->user->getUserId(), $this->profile->getProfileId(), $this->restaurant->getRestaurantId(), "2012-01-01 14:15:16", "somecomment");
		$this->comment->insert($this->getPDO());
	}

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

	public function testGetValidCommentByCommentDateTime() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentDateTime($this->getPDO(), $this->VALID_DATETIME);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test grabbing a Comment by date time that does not exist
	 **/
	public function testGetInvalidCommentByCommentDateTime() {
		// grab a date time that does not exist
		$comment = Comment::getCommentByCommentDateTime($this->getPDO(), "comment doesn't exist(date time)");
		$this->assertNull($comment);
	}

	/**
	 * test grabbing a Comment by content
	 **/
	public function testGetValidCommentByCommentContent() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Profile and insert to into mySQL
		$comment = new Comment(null, $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentContent($this->getPDO(), $this->VALID_CONTENT);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test grabbing a Comment by content that does not exist
	 **/
	public function testGetInvalidCommentByCommentContent() {
		// grab content that does not exist
		$comment = Comment::getCommentByCommentContent($this->getPDO(), "comment doesn't exist(content)");
		$this->assertNull($comment);
	}
}