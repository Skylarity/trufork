<?php

// unit test for profile class
require_once("trufork.php");

// get test parameters
require_once(dirname(__DIR__) . "/php/classes/profile.php");
require_once(dirname(__DIR__) . "/php/classes/user.php");

/**
 *
 * @author kenneth Chavez <kchavez68@cnm.edu>
 **/
class ProfileTest extends TruForkTest {

	/**
	 * email is valid
	 * @var int $VALID_ID
	 **/
	protected $VALID_EMAIL = "kchavez@gmail.com";

	protected $user = null;

	protected $VALID_SALT;

	protected $VALID_HASH;

	/** create set up method for each dependant objects
	 *
	 */
	public final function setUp() {
		// run the default setUp() method first
		parent::setup();
		// create a salt and hash test
		$this->VALID_SALT = bin2hex(openssl_random_pseudo_bytes(32));
		$this->VALID_HASH = $this->VALID_HASH = hash_pbkdf2("sha512", "password1234", $this->VALID_SALT, 262144, 128);

		// create and insert a User to own the test Profile
		$this->user= new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$this->user->insert($this->getPDO());
	}



	/**
	 * test inserting a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertSame($pdoProfile->getUserId(), $this->user->getUserId());
		$this->assertSame($pdoProfile->getEmail(), $this->VALID_EMAIL);
	}
	/**
	 * test inserting a Profile that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidProfile() {
		// create a profile with a non null profileId and watch it fail!!
		$profile = new Profile(ProfileTest::INVALID_KEY, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->insert($this->getPDO());
	}

	/**
	 * test inserting a Profile, editing it, and then updating it
	 **/
	public function testUpdateValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->insert($this->getPDO());


		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertSame($pdoProfile->getEmail(), $this->VALID_EMAIL);
	}

	/**
	 * test updating a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidProfile() {
		// create a Profile and try to update it without actually inserting it
		$profile = new Profile(null, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->update($this->getPDO());
	}

	/**
	 * test creating a Profile and then deleting it
	 **/
	public function testDeleteValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->insert($this->getPDO());

		// delete the Profile from mySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$profile->delete($this->getPDO());

		// grab the data from mySQL and enforce the Profile does not exist
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertNull($pdoProfile);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("profile"));
	}
	/**
	 * test deleting a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidProfile() {
		// create a Profile and try to delete it without actually inserting it
		$profile = new Profile(null, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->delete($this->getPDO());
	}

	/**
	 * test inserting a Profile and regrabbing it from mySQL
	 **/
	public function testGetValidProfileByProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertSame($pdoProfile->getUserId(), $this->user->getUserId());
		$this->assertSame($pdoProfile->getEmail(), $this->VALID_EMAIL);
	}


	/**
	 * test grabbing a Profile that does not exist
	 **/
	public function testGetInvalidProfileByProfileId() {
		// grab a profile id that exceeds the maximum allowable profile id
		$profile = Profile::getProfileByProfileId($this->getPDO(), ProfileTest::INVALID_KEY);
		$this->assertNull($profile);
	}

	/**
	 * test grabbing a Profile by user that does not exist
	 **/
	public function testGetInvalidProfileByUserId() {
		// grab an at handle that does not exist
		$profile = Profile::getProfileByUserId($this->getPDO(), "5");
		$this->assertNull($profile);
	}

	/**
	 * test grabbing a Profile by email
	 **/
	public function testGetValidProfileByEmail() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->user->getUserId(), $this->VALID_EMAIL);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByEmail($this->getPDO(), $this->VALID_EMAIL);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertSame($pdoProfile->getUserId(), $this->user->getUserId());
		$this->assertSame($pdoProfile->getEmail(), $this->VALID_EMAIL);
	}
	/**
	 * test grabbing a Profile by an email that does not exists
	 **/
	public function testGetInvalidProfileByEmail() {
		// grab an email that does not exist
		$profile = Profile::getProfileByEmail($this->getPDO(), "does@not.exist");
		$this->assertNull($profile);
	}
}
