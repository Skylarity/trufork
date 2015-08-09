<?php
/**
 * Created by PhpStorm.
 * User: kennethchavez
 * Date: 8/9/15
 * Time: 3:25 PM
 */

// grab the encrypted properties file
require_once(dirname(__DIR__) . "/php/classes/restaurant.php");


/**
 * Full PHPUnit test for the User class
 *
 * This is a complete PHPUnit test of the Profile class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 **/

class UserTest extends TruForkTest {
	/**
	 * valid at handle to use
	 * @var int $user profile
	 **/
	protected $VALID_ID = "5";
	/**
	 * second valid at handle to use
	 * @var int $user profile
	 **/
	protected $VALID_ID2 = "6";
	/**
	 * valid password salt for userId;
	 * @var string $passwordSalt
	 */
	protected $VALID_SALT;
	/**
	 * valid password hash for userId;
	 * @var string $passwordHash
	 **/
	protected $VALID_HASH;

	public function setup() {
		parent::setup();
		$this->VALID_SALT = bin2hex(openssl_random_pseudo_bytes(32));
		$this->VALID_HASH = $this->VALID_HASH = hash_pbkdf2("sha512", "password1234", $this->VALID_SALT, 262144, 128);
	}

	public function testInsertValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ID, $this->VALID_SALT, $this->VALID_HASH);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByUserId($this->getPDO(), $profile->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertSame($pdoProfile->getAtuserId(), $this->VALID_ID);
		$this->assertSame($pdoProfile->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoProfile->getHash(), $this->VALID_HASH);
	}

	/**
	 * test updating a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidProfile() {
		// create a Profile and try to update it without actually inserting it
		$profile = new Profile(null, $this->VALID_ID, $this->VALID_SALT, $this->VALID_HASH);
		$profile->update($this->getPDO());

	}
	/**
	 * test creating a Profile and then deleting it
	 **/
	public function testDeleteValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ID, $this->VALID_SALT, $this->VALID_HASH);
		$profile->insert($this->getPDO());

		// delete the Profile from mySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$profile->delete($this->getPDO());

		// grab the data from mySQL and enforce the Profile does not exist
		$pdoProfile = Profile::getProfileByUserId($this->getPDO(), $profile->getUserId());
		$this->assertNull($pdoProfile);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("user"));
	}

	/**
	 * test deleting a User that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidUser() {
		// create a Profile and try to delete it without actually inserting it
		$profile = new Profile(null, $this->VALID_ID, $this->VALID_SALT, $this->VALID_HASH);
		$profile->delete($this->getPDO());
	}

	/**
	 * test inserting a User and regrabbing it from mySQL
	 **/
	public function testGetValidUserByUserId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ID, $this->VALID_SALT, $this->VALID_HASH);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByUserId($this->getPDO(), $profile->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertSame($pdoProfile->getUserId(), $this->VALID_ID);
		$this->assertSame($pdoProfile->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoProfile->getHash(), $this->VALID_HASH);
	}

	/**
	 * test grabbing a UserId that does not exist
	 **/
	public function testGetInvalidUseByUserId() {
		// grab a profile id that exceeds the maximum allowable profile id
		$profile = Profile::getProfileByUserId($this->getPDO(), TruForkTest::INVALID_KEY);
		$this->assertNull($profile);
	}



}