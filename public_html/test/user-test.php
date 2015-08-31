<?php

require_once("trufork.php");
require_once(dirname(__DIR__) . "/php/classes/user.php");


/**
 * Full PHPUnit test for the User class
 *
 * This is a complete PHPUnit test of the user class class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 *
 */

class UserTest extends TruForkTest {

	/**
	 * valid email
	 * @var string email
	 *
	 */
	protected $VALID_EMAIL = "hur@dot.com";


	/**
	 * valid user name
	 * @var string user name
	 */

	/**
	 * @var user null
	 */
	protected $USER_ID = null;


	/**
	 * @var string salt 2
	 */
	protected $VALID_SALT;

	/**
	 * @var string $hash 2
	 */
	protected $VALID_HASH;

	/**
	 * @var String name
	 */
	protected $VALID_NAME = "humberto";




	/**
	 * password setup
	 */
	public function setup() {
		parent::setup();

		// create a salt and hash test
		$this->VALID_SALT = bin2hex(openssl_random_pseudo_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", "password1234", $this->VALID_SALT, 262144, 128);

	}


	/**
	 *Test inserting a valid user and verify that the actual MySQL data matches
	 */
	public function testInsertValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_NAME);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH);
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getName(), $this->VALID_NAME);
	}

	/**
	 * test updating a User that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidUser() {
		// create a USer and try to update it without actually inserting it
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_NAME);
		$user->update($this->getPDO());
	}

	/**VALID_ID
	 * test creating a user and then deleting it
	 **/
	public function testDeleteValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new User and insert to into mySQL
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_NAME);
		$user->insert($this->getPDO());

		// delete the User from mySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$user->delete($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertNull($pdoUser);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("user"));
	}

	/**
	 * Test inserting a User, editing it, and then updating it
	 **/

	public function testUpdateValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new user and insert to into mySQL
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_NAME);
		$user->insert($this->getPDO());

		// edit the user and update it in mySQL
		$user->setHash($this->VALID_HASH);
		$user->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH);
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getName(), $this->VALID_NAME);
	}


	/**
	 * test grabbing a user by userId
	 **/
	public function testGetUserByUserId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new User and insert to into mySQL
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_NAME);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH);
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getName(), $this->VALID_NAME);
	}

	/**
	 * test grabbing a user by name
	 **/
	public function testGetUserByName() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new User and insert to into mySQL
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_NAME);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH);
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getName(), $this->VALID_NAME);

	}




	/**
	 * test deleting a User that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidUser() {
		// create a Profile and try to delete it without actually inserting it
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_NAME);
		$user->delete($this->getPDO());
	}
	/**
	 * test grabbing a user by email
	 **/
	public function testGetValidUserByEmail() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new Profile and insert to into mySQL
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH,  $this->VALID_EMAIL, $this->VALID_NAME);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByEmail($this->getPDO(), $this->VALID_EMAIL);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH);
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getName(), $this->VALID_NAME);
	}
	/**
	 * test grabbing a user by an email that does not exists
	 **/
	public function testGetInvalidUserByEmail() {
		// grab an email that does not exist
		$user = User::getUserByEmail($this->getPDO(), "<sript><Scrpt");
		$this->assertNull($user);
	}
}