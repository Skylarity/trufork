<?php
// grab the project test parameters
require_once("trufork.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/likedrestaurant.php");
require_once(dirname(__DIR__) . "/php/classes/user.php");
require_once(dirname(__DIR__) . "/php/classes/profile.php");


/**
 * Full PHPUnit test for the likedRestaurant  class
 *
 * This is a complete PHPUnit test of the LikedRestuarant class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Friend
 * @author H perez <hperezperez@cnm,edu>
 **/
class LikedRestaurantTest extends TruForkTest {


	/**
	 * VALID Profile Id
	 * @var int $PROFILE Id
	 */
	protected $VALID_PROFILEID = 99;
	/**
	 * VALID secondProfile Id
	 * @var int $SECONDPROFILE Id
	 */
	protected $VALID_RESTAURANTID = 100;

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
	 * test inserting a valid LikedRestaturant verifing that
	 * actual mySQL data matches
	 **/
	public function testInsertValidLikedRestaurant() {
// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("likedRestaurant");

// create a new lIKEDRESTAURANT IN mySQL
		$likedRestaurant = newLikedRestaurant($this->VALID_PROFILEID,$this->VALID_RESTAURANTID);
		$likedRestaurant->insert($this->getPDO());
// grab the data from mySQL and enforce the fields match our expectations
		$pdoLikedRestaurant =LikedRestaurant::getLikedRestaurantBYLikedRestaurantId($this->getPDO(), $likedRestaurant->getFriendId()
		);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("likedRestaurant"));
	}

	/**
	 * test inserting a LikedRestaurant   exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidLikedRestaurant () {
// create a likedRestaurant with a non null likedRestaurantId and watch it fail
		$likedRestaurant= new LikedRestaurant(TruForkTest::INVALID_KEY, $this->VALID_RESTAURANTID);
		$likedRestaurant->insert($this->getPDO());
	}

	/**
	 * test inserting a LikedRestaurant
	 * editing it, and then updating it
	 **/
	public function testUpdateValidLikedRestaurant(){
// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("likedRestaurant");

// create a new LikedRestaurant and insert into MySQl
		$likedRestaurant =newLikeRestaurant($this->VALID_PROFILEID,$this->VALID_RESTAURANTID);
		$likedRestaurant->update($this->getPDO());

// edit the LikeRestaurant and update it in mySQL
				$likedRestaurant->setProfileId($this->VALID_RESTAURANTID);
		$likedRestaurant->update($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoLikeRestaurant=LikedRestaurant::LikeRestauratByLikeRestaurantId($this->getPDO(), $likedRestaurant->getLikedRestaurantId);
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("likedRestaurant"));
		$this->assertSame($pdoLikeRestaurant->getProfileId(), $this->VALID_PROFILEID);
		$this->assertSame($pdoLikeRestaurant->getRestaurantId(), $this->VALID_RESTAURANTID);

	}

	/**
	 * test updating a LikedRestaurant that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidProfile() {
// create a Friend and try to update it without actually inserting it
		$likedRestaurant= new LikedRestaurant(truForkTest::INVALID_KEY, $this->VALID_PROFILEID, $this->VALID_RESTAURANTID);
		$likedRestaurant->update($this->getPDO());
	}

	/**
	 * test creating a LikedRestaurant 		 and then deleting it
	 **/
	public function testDeleteValidLikedRestaurant() {
// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("likedRestaurant");

// create a new LikedRestaurant and insert to into mySQL
		$likedRestaurant = new LikedRestaurant($this->VALID_PROFILEID,$this->VALID_RESTAURANTID);
		$likedRestaurant->delete($this->getPDO());

// delete the  LikedRestaurant from mySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("likedRestaurant"));
		$likedRestaurant->delete($this->getPDO());


// grab the data from mySQL and enforce the Friend does not exist
		$pdoFriend = LikedRestaurant::getLikedRestaurantBYLikedRestaurantId($this->getPDO(), $likedRestaurant->getLikedRestaurantId());
		$this->assertNull($pdoLikedRestaurant);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("likedRestaurant"));
	}

	/**
	 * test deleting a LIKEDRESTAURANT that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidLikedRestaurant() {
// create a Friend and try to delete it without actually inserting it
		$likedRestaurantd = new LikedRestaurant(truForkTest::INVALID_KEY, $this->VALID_PROFILEID, $this->VALID_RESTAURANTID);
		$likedRestaurantd->delete($this->getPDO());
	}

	/**
	 * test inserting a LikedRestaurant and regrabbing it from mySQL
	 **/
	public function testGetValidLikedRestaurantByLikedRestaurantId() {
// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("likedRestaurant");

// create a new Friend and insert to into mySQL
		$likedRestauant = new LikedRestaurant($this->VALID_PROFILEID, $this->VALID_RESTAURANTID);
		$likedRestaurant->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoLikedRestaurantd =LikedRestaurant::getLikedRestaurantBYLikedRestaurantId($this->getPDO(), $likedRestaurant->getLikedRestaurantId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("likedRestaurant"));
		$this->assertSame($pdoLikedRestaurant->getProfileId(), $this->VALID_PROFILEID);
		$this->assertSame($pdoLikedRestaurant->getRestaurantId(), $this->VALID_RESTAURANTID);

	}

	/**
	 * test grabbing a Friend that does not exist
	 **/
	public function testGetInvalidFriendByFriendId() {
// grab a friend id that exceeds the maximum allowable friend id
		$likedRestaurant = LikedRestaurant::getLikedRestaurantBYLikedRestaurantId($this->getPDO(), TruForkTest::INVALID_KEY, TruForkTest::INVALID_KEY);
		$this->assertNull($likedRestaurant);
	}
}