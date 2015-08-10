<?php
/**
 * Created by PhpStorm.
 * User: kennethchavez
 * Date: 8/9/15
 * Time: 3:25 PM
 */

// grab the encrypted properties file
// Get the test parameters
require_once("trufork.php");
require_once(dirname(__DIR__) . "/php/classes/user.php");


/**
 * Full PHPUnit test for the User class
 *
 * This is a complete PHPUnit test of the user class class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 *
 * @property User profile
 */

class UserTest extends TruForkTest {
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

	protected $user = null;


	/**
	 * profile set up
	 */
	public function setup() {
		parent::setup();

		// create a salt and hash test
		$this->VALID_SALT = bin2hex(openssl_random_pseudo_bytes(32));
		$this->VALID_HASH = $this->VALID_HASH = hash_pbkdf2("sha512", "password1234", $this->VALID_SALT, 262144, 128);

		$this->user = new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$this->user->insert($this->getPDO());
	}

	public function testInsertValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new Profile and insert to into mySQL
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH);
	}

	/**
	 * test updating a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidUser() {
		// create a Profile and try to update it without actually inserting it
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$user->update($this->getPDO());

	}
	/**VALID_ID
	 * test creating a Profile and then deleting it
	 **/
	public function testDeleteValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new Profile and insert to into mySQL
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$user->insert($this->getPDO());

		// delete the Profile from mySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$user->delete($this->getPDO());

		// grab the data from mySQL and enforce the Profile does not exist
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertNull($pdoUser);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("user"));
	}

	/**
	 * test deleting a User that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidUser() {
		// create a Profile and try to delete it without actually inserting it
		$user = new User(null, $this->VALID_SALT, $this->VALID_HASH);
		$user->delete($this->getPDO());
	}

}