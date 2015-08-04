<?php

/**
 * Creation of liked restaurant class for trufork
 *
 * This script is designed to create liked restaurant for trufork.
 *
 * @author  Humberto Perez <hperezperez@cnm,edu>
 *
 **/
class LikedRestaurant {
	/** above is our class
	 *  it's a weak entity
	 * it has no primary key
	 * it uses profileId and restaurantId as foreign keys
	 **/
	private $profileId;
	/**
	 * @var int foreing key
	 */
	private $restaurantId;

	/** constructor method for likeRestaurant
	 * @param int$profileId
	 * @param int $likeRestaurant
	 * @throws InvalidArgumentException if the data is invalid
	 * @throws RangeException if data out of range
	 * @throws Exception For all other cases
	 *
	 **/
	public function __construct($profileId, $restaurantIdl) {
		try {
			$this->setProfileId($profiledId);
			$this->setRestaurantId($restaurantId);
		}	catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}	catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}	catch(Exception $exception) {
			// Rethrow exception to the caller
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor for restaurant ID
	 *
	 * @return int
	 */
	public function getRestaurantId() {
		return $this->restaurantId;
	}

	/**
	 * Mutator for restaurant ID
	 *
	 * @param mixed $newRestaurantId
	 */
	public function setRestaurantId($newRestaurantId) {
		// Base case: If the restaurant ID is null, this is a new restaurant without a MySQL assigned ID
		if($newRestaurantId === null) {
			$this->restaurantId = null;
			return;
		}

		// Verify the new restaurant ID
		$newRestaurantId = filter_var($newRestaurantId, FILTER_VALIDATE_INT);
		if($newRestaurantId === false) {
			throw(new InvalidArgumentException("Restaurant ID is not a valid integer"));
		}

		// Verify the new restaurant ID is positive
		if($newRestaurantId <= 0) {
			throw(new RangeException("Restaurant ID is not positive"));
		}

		// Convert and store the new restaurant ID
		$this->restaurantId = intval($newRestaurantId);
	}

	/**
	 * Accessor for Profile ID
	 *
	 * @return int
	 */
	public function geProfileId() {
		return $this->profileId;
	}

	/**
	 * Mutator for Profile ID
	 *
	 * @param in $newProfileId
	 */
	public function setProfileId($newProfileID){}


	/**
	 * Inserts this likedrestaurant into MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function insert(PDO &$pdo) {
		// Make sure this is a new likedrestaurant
		if($this->restaurantId !== null) {
			throw(new PDOException("Not a new likedrestaurant"));
		}

		// Create query template
		$query = "INSERT INTO likedrestaurant( retaurantId, profileId ) VALUES( :restarauntID, :profileId)";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the template
		$parameters = array("address" => $this->getAddress(), "phone" => $this->getPhone(), "forkRating" => $this->getForkRating(), "facilityKey" => $this->getFacilityKey(), "googleId" => $this->getGoogleId(), "name" => $this->getName());
		$statement->execute($parameters);

		// Update the null restaurant ID with what MySQL has generated
		$this->setRestaurantId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this likedrestaurant from MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this likedrestaurant already exists
		if($this->getRestaurantId() === null) {
			throw(new PDOException("Unable to delete a likedrestaurant that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM restaurant WHERE restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("restaurantId" => $this->getRestaurantId());
		$statement->execute($parameters);
	}

	/**
	 * Updates this likedrestaurant in MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function update(PDO &$pdo) {
		// Make sure this likedrestaurant exists
		if($this->getRestaurantId() === null) {
			throw(new PDOException("Unable to update a restaurant that does not exist"));
		}

		// Create query template
		$query = "UPDATE restaurant SET address = :address, phone = :phone, forkRating = :forkRating, facilityKey = :facilityKey, googleId = :googleId, name = :name WHERE restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("address" => $this->getAddress(), "phone" => $this->getPhone(), "forkRating" => $this->getForkRating(), "facilityKey" => $this->getFacilityKey(), "googleId" => $this->getGoogleId(), "name" => $this->getName(), "restaurantId" => $this->getRestaurantId());
		$statement->execute($parameters);
	}

	/**
	 * Gets the likedrestaurant by restaurant ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $restaurantId restaurant ID to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantById(PDO &$pdo, $restaurantId) {
		// Sanitize the ID before searching
		$restaurantId = filter_var($restaurantId, FILTER_SANITIZE_NUMBER_INT);
		if($restaurantId === false) {
			throw(new PDOException("Restaurant ID is not an integer"));
		}
		if($restaurantId <= 0) {
			throw(new PDOException("Profile ID is not positive"));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind restaurantId to placeholder
		$parameters = array("restaurantId" => Restaurant::getRestaurantId());
		$statement->execute($parameters);

		// Grab the likedrestaurant from MySQL
		try {
			$restaurant = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				// new LIkedRestaurant($restaurantId, $profileId)
				$likedrestaurant = new LikedRestaurant($row["restaurantId"], $row["profileId"]);
			}
		} catch(Exception $exception) {
			// If the row couldn't be converted, rethrow it
			throw(new PDOException($e->getMessage(), 0, $e));
		}

		return ($likedrestaurant);
	}

	/**
	 * Gets the restaurant by address
	 *


		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE address = :address";
		$statement = $pdo->prepare($query);

		// Bind address to placeholder
		$parameters = array("address" => Restaurant::getAddress());
		$statement->execute($parameters);

		/**
	 * Gets the restaurant by phone
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $phone restaurant phone number to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related e


	/**
	 * Get restaurants by TruFork rating
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $forkRating TruFork rating to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */


		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE forkRating = :forkRating";
		$statement = $pdo->prepare($query);

		// Bind rating to placeholder
		$parameters = array("forkRating" => Restaurant::getForkRating());
		$statement->execute($parameters);

		// Build an array of restaurants
		$restaurants = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				// new Restaurant($restaurantId, $googleId, $facilityKey, $address, $phone, $forkRating)
				$restaurant = new Restaurant($row["restaurantId"], $row["googleId"], $row["facilityKey"], $row["name"], $row["address"], $row["phone"], $row["forkRating"]);
				$restaurants[$restaurants->key()] = $restaurant;
				$restaurants->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($restaurants);
	}

	/**
	 * Gets the restaurant by facility key
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $facilityKey facility key to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantByFacilityKey(PDO &$pdo, $facilityKey) {
		// Sanitize the key before searching
		$facilityKey = trim($facilityKey);
		$facilityKey = filter_var($facilityKey, FILTER_SANITIZE_STRING);
		if(empty($facilityKey)) {
			throw(new PDOException("Facility key is invalid"));
		}


	/**
	 * Gets the likedrestaurant by profiele ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $googleId Google ID to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantByGoogleId(PDO &$pdo, $googleId) {
		// Sanitize the ID before searching
		$googleId = trim($googleId);
		$googleId = filter_var($googleId, FILTER_SANITIZE_STRING);
		if(empty($googleId)) {
			throw(new PDOException("Google ID is invalid"));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE googleId = :googleId";
		$statement = $pdo->prepare($query);

		// Bind ID to placeholder
		$parameters = array("googleId" => Restaurant::getGoogleId());
		$statement->execute($parameters);

		// Grab the restaurant from MySQL
		try {
			$restaurant = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				// new Restaurant($restaurantId, $googleId, $facilityKey, $name, $address, $phone, $forkRating)
				$restaurant = new Restaurant($row["restaurantId"], $row["googleId"], $row["facilityKey"], $row["name"], $row["address"], $row["phone"], $row["forkRating"]);
			}
		} catch(Exception $e) {
			// If the row couldn't be converted, rethrow it
			throw(new PDOException($e->getMessage(), 0, $e));
		}

		return ($restaurant);
	}


	}
	/**
	 * Gets all restaurants
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @return SplFixedArray of Restaurants found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getAllRestaurants(PDO &$pdo) {
		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Build an array of restaurants
		$restaurants = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				// new Restaurant($restaurantId, $googleId, $facilityKey, $name, $address, $phone, $forkRating)
				$restaurant = new Restaurant($row["restaurantId"], $row["googleId"], $row["facilityKey"], $row["name"], $row["address"], $row["phone"], $row["forkRating"]);
				$restaurants[$restaurants->key()] = $restaurant;
				$restaurants->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($restaurants);
	}

}




