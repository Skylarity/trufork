<?php
// grab the project test parameters
require_once(dirname(__DIR__) . "/test/trufork.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/comment.php");
require_once(dirname(__DIR__) . "/php/classes/restaurant.php");
require_once(dirname(__DIR__) . "/php/classes/user.php");

/**
 * PHPUnit test for the Comment class
 *
 * This tests the Comment Class for truFork.
 *
 * @see Comment
 * @author Trevor Rigler tarigler@gmail.com
 **/
class CommentTest extends TruForkTest {

	/**
	 * valid  datetime to use
	 * @var DateTime dateTime
	 **/
	protected $VALID_DATETIME = null;

	/**
	 * second valid  datetime to use
	 * @var DateTime dateTime
	 **/
	protected $VALID_DATETIME2 = null;

	/**
	 * valid content to use
	 * @var string content
	 **/
	protected $VALID_CONTENT = "bigoldstringofnonsense";

	/**
	 * valid User that created the comment
	 * @var User $user
	 **/
	protected $user = null;

	/**
	 * @var string $VALID_SALT
	 */
	protected $VALID_SALT;
	/**
	 * @VAR string $VALID_HASH
	 */
	protected $VALID_HASH;

	/**
	 * valid Restaurant upon which the comment is made
	 * @var Restaurant $restaurant
	 **/
	protected $restaurant = null;


	/**
	 *
	 * create dependent objects UserId & RestaurantId before running each test
	 * references getSetUpOperation from test/trufork.php
	 **/

	public final function setUp() {
		// run the default setup() method first
		parent::setUp();

		// Initialize DateTime objects
		$this->VALID_DATETIME = DateTime::createFromFormat("Y-m-d H:i:s", "2015-01-01 15:16:17");
		$this->VALID_DATETIME2 = DateTime::createFromFormat("Y-m-d H:i:s", "2013-01-01 15:12:15");

		//create salt and hash for User
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$hash = hash_pbkdf2("sha512", "password1234", $salt, 262144, 128);
//		$this->user = new User(null, $salt, $hash, );
//		$this->user->insert($this->getPDO());

		//create and insert a User to own the test Comment
		$this->user = new User(null, $hash, $salt, "JoeBob", "joebob@sixfinger.com");
		$this->user->insert($this->getPDO());

		//create and insert a Restaurant to own the test Comment
		$this->restaurant = new Restaurant(null, "ChIJ18Mh_sa6woARKLIrL9eOxTs", "ABCD12345678", "Sticky Rice", "317 South Broadway, Los Angeles, CA 90013", "+18185551212", "5");
		$this->restaurant->insert($this->getPDO());
	}

	/**
	 * test inserting a valid Comment and verify that the actual mySQL data matches
	 **/
	public function testInsertValidComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
		$this->assertEquals($pdoComment->getDateTime(), $this->VALID_DATETIME);
	}

	/**
	 * test inserting a Comment that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidComment() {
		// create a comment with a non null commentId and watch it fail
		$comment = new Comment(TruforkTest::INVALID_KEY, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());
	}

	/**
	 * test inserting a Comment, editing it, and then updating it
	 **/
	public function testUpdateValidComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// edit the Comment and update it in mySQL
		$comment->setDateTime($this->VALID_DATETIME2);
		$comment->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getDateTime(), $this->VALID_DATETIME2);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test updating a Comment that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidComment() {
		// create a Comment and try to update it without actually inserting it
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->update($this->getPDO());
	}

	/**
	 * test creating a Comment and then deleting it
	 **/
	public function testDeleteValidComment() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
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
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->delete($this->getPDO());
	}

	/**
	 * test inserting a Comment and regrabbing it from mySQL
	 **/
	public function testGetValidCommentByCommentId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getDateTime(), $this->VALID_DATETIME);
		$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
	}

	/**
	 * test grabbing a Comment by User Id
	 **/
	public function testGetValidCommentByUserId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComments = Comment::getCommentByUserId($this->getPDO(), $this->user->getUserId());
		foreach($pdoComments as $pdoComment) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
			$this->assertSame($pdoComment->getUserId(), $this->user->getUserId());
		}
	}

	/**
	 * test grabbing a Comment by Restaurant Id
	 **/
	public function testGetValidCommentByRestaurantId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComments = Comment::getCommentByRestaurantId($this->getPDO(), $this->restaurant->getRestaurantId());
		foreach($pdoComments as $pdoComment) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
			$this->assertSame($pdoComment->getRestaurantId(), $this->restaurant->getRestaurantId());
		}
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
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComments = Comment::getCommentByDateTime($this->getPDO(), $this->VALID_DATETIME);
		foreach($pdoComments as $pdoComment) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
			$this->assertEquals($pdoComment->getDateTime(), $this->VALID_DATETIME);
			$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
		}
	}

	/**
	 * test grabbing a Comment by date time that does not exist
	 **/
	public function testGetInvalidCommentByCommentDateTime() {
		// grab a date time that does not exist
		$comment = Comment::getCommentByDateTime($this->getPDO(), "comment doesn't exist(date time)");
		$this->assertNull($comment);
	}

	/**
	 * test grabbing a Comment by content
	 **/
	public function testGetValidCommentByCommentContent() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$comment = new Comment(null, $this->user->getUserId(), $this->restaurant->getRestaurantId(), $this->VALID_DATETIME, $this->VALID_CONTENT);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComments = Comment::getCommentByCommentContent($this->getPDO(), $this->VALID_CONTENT);
		foreach($pdoComments as $pdoComment) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("comment"));
			$this->assertEquals($pdoComment->getDateTime(), $this->VALID_DATETIME);
			$this->assertSame($pdoComment->getContent(), $this->VALID_CONTENT);
		}
	}

	/**
	 * test grabbing a Comment by content that does not exist
	 **/
	public function testGetInvalidCommentByCommentContent() {
		// grab content that does not exist
		$comments = Comment::getCommentByCommentContent($this->getPDO(), "no comment containing this content exists");
		foreach($comments as $comment) {
			$this->assertNull($comment);
		}
	}
}