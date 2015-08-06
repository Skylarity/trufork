<?php
// grab the project test parameters
require_once("trufork.php");

// grab the class under scrutiny
  require_once(dirname(__DIR__) . "/php/classes/friend.php");


/**
* Full PHPUnit test for the Friend  class
*
* This is a complete PHPUnit test of the Friend class. It is complete because *ALL* mySQL/PDO enabled methods
* are tested for both invalid and valid inputs.
*
* @see Friend
* @author Dylan McDonald <dmcdonald21@cnm.edu>
**/
class FriendTest extends TruForkTest {
/**
* valid at handle to use
* @var string $VALID_ATHANDLE
**/
protected $VALID_ATHANDLE = "@phpunit";
/**
* second valid at handle to use
* @var string $VALID_ATHANDLE2
**/
protected $VALID_ATHANDLE2 = "@passingtests";
/**
* valid email to use
* @var string $VALID_EMAIL
**/
protected $VALID_EMAIL = "test@phpunit.de";
/**
* valid phone number to use
* @var string $VALID_PHONE
**/
protected $VALID_PHONE = "+12125551212";

/**
* test inserting a valid Friend and verify that the actual mySQL data matches
**/
public function testInsertValidFriend() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByFriendId($this->getPDO(), $friend->getFriendId());
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("friend"));
$this->assertSame($pdoFriend->getAtHandle(), $this->VALID_ATHANDLE);
$this->assertSame($pdoFriend->getEmail(), $this->VALID_EMAIL);
$this->assertSame($pdoFriend->getPhone(), $this->VALID_PHONE);
}

/**
* test inserting a Friend that already exists
*
* @expectedException PDOException
**/
public function testInsertInvalidProfile() {
// create a profile with a non null profileId and watch it fail
$friend = new Friend(TruForkTest::INVALID_KEY, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->insert($this->getPDO());
}

/**
* test inserting a Profile, editing it, and then updating it
**/
public function testUpdateValidFriend() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("profile");

// create a new Friend and insert to into mySQL
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->insert($this->getPDO());

// edit the Friend and update it in mySQL
$friend->setAtHandle($this->VALID_ATHANDLE2);
$friend->update($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByFriendId($this->getPDO(), $friend->getFriendId());
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
$this->assertSame($pdoFriend->getAtHandle(), $this->VALID_ATHANDLE2);
$this->assertSame($pdoFriend->getEmail(), $this->VALID_EMAIL);
$this->assertSame($pdoFriend->getPhone(), $this->VALID_PHONE);
}

/**
* test updating a Profile that does not exist
*
* @expectedException PDOException
**/
public function testUpdateInvalidProfile() {
// create a Friend and try to update it without actually inserting it
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->update($this->getPDO());
}

/**
* test creating a Friend and then deleting it
**/
public function testDeleteValidFriend() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->insert($this->getPDO());

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
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->delete($this->getPDO());
}

/**
* test inserting a Friend and regrabbing it from mySQL
**/
public function testGetValidFriendByFriendId() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByFriendId($this->getPDO(), $friend->getFriendId());
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
$this->assertSame($pdoFriend->getAtHandle(), $this->VALID_ATHANDLE);
$this->assertSame($pdoFriend->getEmail(), $this->VALID_EMAIL);
$this->assertSame($pdoFriend->getPhone(), $this->VALID_PHONE);
}

/**
* test grabbing a Friend that does not exist
**/
public function testGetInvalidFriendByFriendId() {
// grab a friend id that exceeds the maximum allowable friend id
$friend = Friend::getFriendByFriendId($this->getPDO(), TruForkTest::INVALID_KEY);
$this->assertNull($friend);
}

public function testGetValidFriendByAtHandle() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByAtHandle($this->getPDO(), $this->VALID_ATHANDLE);
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("friend"));
$this->assertSame($pdoFriend->getAtHandle(), $this->VALID_ATHANDLE);
$this->assertSame($pdoFriend->getEmail(), $this->VALID_EMAIL);
$this->assertSame($pdoFriend->getPhone(), $this->VALID_PHONE);
}

/**
* test grabbing a Friend by at handle that does not exist
**/
public function testGetInvalidProfileByAtHandle() {
// grab an at handle that does not exist
$friend = Friend::getFriendByAtHandle($this->getPDO(), "@doesnotexist");
$this->assertNull($friend);
}

/**
* test grabbing a Friend by email
**/
public function testGetValidFriendByEmail() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("friend");

// create a new Friend and insert to into mySQL
$friend = new Friend(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
$friend->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoFriend = Friend::getFriendByEmail($this->getPDO(), $this->VALID_EMAIL);
$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("friend"));
$this->assertSame($pdoFriend->getAtHandle(), $this->VALID_ATHANDLE);
$this->assertSame($pdoFriend->getEmail(), $this->VALID_EMAIL);
$this->assertSame($pdoFriend->getPhone(), $this->VALID_PHONE);
}

/**
* test grabbing a Friend by an email that does not exists
**/
public function testGetInvalidFriendByEmail() {
// grab an email that does not exist
$friend = Friend::getFriendByEmail($this->getPDO(), "does@not.exist");
$this->assertNull($friend);
}
}