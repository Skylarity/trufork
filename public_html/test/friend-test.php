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
	 * VALID firstProfile Id
	 * @var int $FIRSTPROFILE Id
	 */
protected $VALID_FIRSTPROFILEID = 99;
	/**
	 * VALID secondProfile Id
	 * @var int $SECONDPROFILE Id
	 */
protected $VALID_SECONDPROFILEID = 100;
	/**
	 * VALID date friended to use
	 * var date
	 */
protected $VALID_DATEFRIENDED = "2015-03-23 15:23:04";
	/**
 * Profile that has the friend(s); this is for foreign key relations
 * @var Friend $profile
 */
	protected $profile = null;
	protected $user = null;

	/**
	 * Create dependent objects before running each test
	 */
	public final function setUp() {
		// Run the default setUp() method first
		parent::setUp();

		// create and insert a Profile to own the test Tweet
//		$formattedDateFriended= Datetime::createFromFormat("Y-m-d H:i:s",$this->VALID_DATEFRIENDED);
		$this->user1 = new user(null, "233", "345");
		$this->user1->insert($this->getPDO());
		$this->user2 = new user(null, "233", "345");
		$this->user2->insert($this->getPDO());
		$this->profile1 = new profile(null, $this->user1->getUserId(), "user1@example.com");
		$this->profile2 = new profile(null, $this->user2->getUserId(), "user2@example.com");
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
$friend = new Friend($this->VALID_FIRSTPROFILEID,$this->VALID_SECONDPROFILEID, $this->VALID_DATEFRIENDED);
$friend->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByFriendId($this->getPDO(), $friend->getFriendId()
);
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("friend"));
$this->assertSame($pdoFriend->getDate(),$this->VALID_DATEFRIENDED);
}

/**
* test inserting a Friend that already exists
*
* @expectedException PDOException
**/
public function testInsertInvalidFriend(){
// create a friend with a non null friendId and watch it fail
$friend = new Friend(TruForkTest::INVALID_KEY, $this->VALID_SECONDPROFILEID, $this->VALID_DATEFRIENDED);
$friend->insert($this->getPDO());
}

/**
* test inserting a Friend, editing it, and then updating it
**/
public function testUpdateValidFriend() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("profile");

// create a new Friend and insert to into mySQL
$friend = new Friend($this->VALID_FIRSTPROFILEID,$this->VALID_SECONDPROFILEID,$this->VALID_DATEFRIENDED);
$friend->update($this->getPDO());

// edit the Friend and update it in mySQL
$friend->setFirstProfileId($this->VALID_SECONDPROFILEID);
$friend->update($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByFriendId($this->getPDO(), $friend->getFriendId);
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
$this->assertSame($pdoFriend->getFirstProfileId(), $this->VALID_FIRSTPROFILEID);
$this->assertSame($pdoFriend->getSecondProfileId(), $this->VALID_SECONDPROFILEID);
$this->assertSame($pdoFriend->getDatefriended(), $this->VALID_DATEFRIENDED);
}

/**
* test updating a Profile that does not exist
*
* @expectedException PDOException
**/

public function testUpdateInvalidProfile() {
// create a Friend and try to update it without actually inserting it
$friend = new Friend(truForkTest::INVALID_KEY, $this->VALID_SECONDPROFILEID, $this->VALID_DATEFRIENDED);
$friend->update($this->getPDO());
}

/**
* test creating a Friend and then deleting it
**/
public function testDeleteValidFriend() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
$friend = new Friend($this->VALID_FIRSTPROFILEID, $this->VALID_SECONDPROFILEID, $this->VALID_DATEFRIENDED);
$friend->delete($this->getPDO());

// delete the Profile from mySQL
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("friend"));
$friend->delete($this->getPDO());


// grab the data from mySQL and enforce the Friend does not exist
$pdoFriend = Friend::getFriendByFriendId($this->getPDO(), $friend->getFriendId());
$this->assertNull($pdoFriend);
$this->assertSame($numRows, $this->getConnection()->getRowCount("friend"));
}

/**
* test deleting a Friend that does not exist
*
* @expectedException PDOException
**/
public function testDeleteInvalidFriend() {
// create a Friend and try to delete it without actually inserting it
$friend = new Friend(truForkTest::INVALID_KEY, $this->VALID_SECONDPROFILEID, $this->VALID_DATEFRIENDED);
$friend->delete($this->getPDO());
}

/**
* test inserting a Friend and regrabbing it from mySQL
**/
public function testGetValidFriendByFriendId() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
$friend = new Friend($this->VALID_FIRSTPROFILEID, $this->VALID_SECONDPROFILEID, $this->VALID_DATEFRIENDED);
$friend->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByFriendId($this->getPDO(), $friend->getFriendId());
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
$this->assertSame($pdoFriend->getFirstProfileId(), $this->VALID_FIRSTPROFILEID);
$this->assertSame($pdoFriend->getSecondProfileId(), $this->VALID_SECONDPROFILEID);
$this->assertSame($pdoFriend->getDateFriended(), $this->VALID_DATEFRIENDED);
}

/**
* test grabbing a Friend that does not exist
**/
public function testGetInvalidFriendByFriendId() {
// grab a friend id that exceeds the maximum allowable friend id
$friend = Friend::getFriendByFriendId($this->getPDO(), TruForkTest::INVALID_KEY, TruForkTest::INVALID_KEY);
$this->assertNull($friend);
}






}