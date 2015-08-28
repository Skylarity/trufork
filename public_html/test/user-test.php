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
 * @property  USER_3
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

	/**
	 * valid email
	 * @var string email
	 *
	 */
	protected $VALID_EMAIL = "humberto@hemberto.com";

	/**
	 * valid User Name
	 * @var string name
	 *
	 */
	protected  $USERNAME = "humberto123";
	/**
	 * valid user name
	 * @var string user name
	 */

	/**
	 * @var user null
	 */
	protected $USER_ID = null;

	/**
	 * @var user USER_3
	 */
	protected $USER_3 = "5";

	/**
	 * @var string salt 2
	 */
	protected $VALID_SALT_2;
	/**
	 * @var string $hash 2
	 */
	protected $VALID_HASH_2;



	/**
	 * profile set up
	 */
	public function setup() {
		parent::setup();

		// create a salt and hash test
		$this->VALID_SALT = bin2hex(openssl_random_pseudo_bytes(32));
		$this->VALID_HASH = $this->VALID_HASH = hash_pbkdf2("sha512", "password1234", $this->VALID_SALT, 262144, 128);

		// salt and hash 2
		$this->VALID_SALT_2 = bin2hex(openssl_random_pseudo_bytes(32));
		$this->VALID_HASH_2 = $this->VALID_HASH = hash_pbkdf2("sha512", "password1234", $this->VALID_SALT, 262144, 128);

		// set up user test in mySQL
		$this->USER_ID = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$this->USER_ID->insert($this->getPDO());

		// set up user2 test in mySQL
		$this->USER_3 = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$this->USER_3->insert($this->getPDO());
	}


	/**
	 * test updating a User that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidUser() {
		// create a Profile and try to update it without actually inserting it
		$user = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$user->update($this->getPDO());
	}




	/**VALID_ID
	 * test creating a user and then deleting it
	 **/
	public function testDeleteValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new Profile and insert to into mySQL
		$user = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
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
	 * Test inserting a User, editing it, and then updating it
	 **/

	public function testUpdateValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new user and insert to into mySQL
		$user = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$user->insert($this->getPDO());

		// edit the user and update it in mySQL
		$user->setHash($this->VALID_HASH_2);
		$user->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getUserName(),$this->USERNAME);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH_2);
	}

	/**
	 *
	 */
	public function testInsertValidUser() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		$user = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertLessThan($pdoUser->getUserId(), 0);
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getUserName(), $this->USERNAME);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH_2);
	}

	/**
	 * test grabbing a user by userId
	 **/
	public function testGetUserByUserId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new Profile and insert to into mySQL
		$user = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getUserName(), $this->USERNAME);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH_2);
	}


	/**
	 * test deleting a User that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidUser() {
		// create a Profile and try to delete it without actually inserting it
		$user = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$user->delete($this->getPDO());
	}
	/**
	 * test grabbing a user by email
	 **/
	public function testGetValidUserByEmail() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("user");

		// create a new Profile and insert to into mySQL
		$user = new User(null, $this->USERNAME, $this->VALID_EMAIL, $this->VALID_SALT, $this->VALID_HASH);
		$user->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoUser = User::getUserByEmail($this->getPDO(), $this->VALID_EMAIL);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
		$this->assertSame($pdoUser->getEmail(), $this->VALID_EMAIL);
		$this->assertSame($pdoUser->getUserName(), $this->USERNAME);
		$this->assertSame($pdoUser->getSalt(), $this->VALID_SALT);
		$this->assertSame($pdoUser->getHash(), $this->VALID_HASH_2);
	}
}