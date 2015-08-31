<?php
// grab the project test parameters
require_once("trufork.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/friend.php");
require_once(dirname(__DIR__) . "/php/classes/user.php");
require_once(dirname(__DIR__) . "/php/classes/profile.php");


/**
 * Full PHPUnit test for the Friend  class
 *
 * This is a complete PHPUnit test of the Friend class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Friend
 * @author H perez <hperezperez@cnm,edu>
 **/
class FriendTest extends TruForkTest {

	/**
	 * VALID date friended to use
	 * var date
	 */
	protected $VALID_DATEFRIENDED = null;
	/**
	 * Profile that has the friend(s); this is for foreign key relations
	 * @var Profile $profile1
	 */
	protected $profile1 = null;

	/**
	 * Profile that has the friend(s); this is for foreign key relations
	 * @var Profile $profile2
	 */
	protected $profile2 = null;

	/**
	 * Profile that has the friend(s); this is for foreign key relations
	 * @var User $user1
	 */
	protected $user1 = null;

	/**
	 * Profile that has the friend(s); this is for foreign key relations
	 * @var User $user2
	 */
	protected $user2 = null;

	/**
	 * @var string $VALID_SALT
	 */
	protected $VALID_SALT;
	/**
	 * @VAR string $VALID_HASH
	 */
	protected $VALID_HASH;

	/**
	 * Create dependent objects before running each test
	 */
	public final function setUp() {
		// Run the default setUp() method first
		parent::setUp();
		// Create date
		$this->VALID_DATEFRIENDED = DateTime::createFromFormat("Y-m-d H:i:s", "2015-03-23 15:23:04");

		//create  a salt and hash test
		$this->VALID_SALT = bin2hex(openssl_random_pseudo_bytes(32));
		$this->VALID_HASH = $this->VALID_HASH = hash_pbkdf2("sha512", "password4321", $this->VALID_SALT, 262144, 128);

		//create and insert a user test profile
		$this->user1 = new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$this->user2 = new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$this->user1->insert($this->getPDO());
		$this->user2->insert($this->getPDO());

		$this->profile1 = new Profile(null, $this->user1->getUserId(), "user1@example.com");
		$this->profile2 = new Profile(null, $this->user2->getUserId(), "user2@example.com");
		$this->profile1->insert($this->getPDO());
		$this->profile2->insert($this->getPDO());

	}


	/**
	 * test inserting a valid Friend and verify that the actual mySQL data matches
	 **/
	public function testInsertValidFriend() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("friend");

		// create a new Friend and insert to into mySQL
		$friend = new Friend($this->profile1->getProfileId(), $this->profile2->getProfileId(), $this->VALID_DATEFRIENDED);
		$friend->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoFriends = Friend::getFriendByProfileId($this->getPDO(), $friend->getFirstUserId());
		foreach($pdoFriends as $pdoFriend) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("friend"));
			$this->assertEquals($pdoFriend->getDateFriended(), $this->VALID_DATEFRIENDED);
		}
	}

	/**
	 * test inserting a Friend that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidFriend() {
// create a friend with a non null friendId and watch it fail
		$friend = new Friend(TruForkTest::INVALID_KEY, TruForkTest::INVALID_KEY, $this->VALID_DATEFRIENDED);
		$friend->insert($this->getPDO());
	}

	/**
	 * test creating a Friend and then deleting it
	 **/
	public function testDeleteValidFriend() {
// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
		$friend = new Friend($this->profile1->getProfileId(), $this->profile2->getProfileId(), $this->VALID_DATEFRIENDED);
		$friend->delete($this->getPDO());

// delete the Profile from mySQL
		$this->assertSame($numRows, $this->getConnection()->getRowCount("friend"));
		$friend->delete($this->getPDO());


// grab the data from mySQL and enforce the Friend does not exist
		$pdoFriends = Friend::getFriendByProfileId($this->getPDO(), $friend->getFirstUserId());
		foreach($pdoFriends as $pdoFriend) {
			$this->assertNull($pdoFriend);
			$this->assertSame($numRows, $this->getConnection()->getRowCount("friend"));
		}
	}

	/**
	 * test deleting a Friend that does not exist
	 *
	 * @expectedException InvalidArgumentException
	 **/
	public function testDeleteInvalidFriend() {
// create a Friend and try to delete it without actually inserting it
		$friend = new Friend(null, null, $this->VALID_DATEFRIENDED);
		$friend->delete($this->getPDO());
	}

	/**
	 * test inserting a Friend and regrabbing it from mySQL
	 **/
	public function testGetValidFriendByProfileId() {
// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
		$friend = new Friend($this->profile1->getProfileId(), $this->profile2->getProfileId(), $this->VALID_DATEFRIENDED);
		$friend->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoFriends = Friend::getFriendByProfileId($this->getPDO(), $friend->getFirstUserId());
		foreach($pdoFriends as $pdoFriend) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("friend"));
			$this->assertSame($pdoFriend->getFirstProfileId(), $this->profile1->getProfileId());
			$this->assertSame($pdoFriend->getSecondProfileId(), $this->profile2->getProfileId());
			$this->assertEquals($pdoFriend->getDateFriended(), $this->VALID_DATEFRIENDED);
		}
	}

	/**
	 * test grabbing a Friend that does not exist
	 **/
	public function testGetInvalidFriendByProfileId() {
// grab a friend id that exceeds the maximum allowable friend id
		$friends = Friend::getFriendByProfileId($this->getPDO(), TruForkTest::INVALID_KEY);
		foreach($friends as $friend) {
			$this->assertNull($friend);
		}
	}


}