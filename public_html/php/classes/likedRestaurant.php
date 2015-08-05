<?php

/**
 * Creation of liked restaurant class for trufork
 *
 * This script is designed to create liked restaurant for trufork.
 *
 * @author  Humberto Perez <hperezperez@cnm.edu>
 *
 **/
 class LikedRestaurant {
	/** above is our class
	 *  it's a weak entity
	 * it has no primary key
	 * it uses profileId and restaurantId as foreign keys
	 **/
	private $restaurantId;
	 /**
	  *profile Id is a foreign key
	  * @var int foreing key
	 **/
	private $profileId;

	/** constructor method for likeRestaurant
	 * @param int $profileId
	 * @param int $likeRestaurant
	 * @throws InvalidArgumentException if the data is invalid
	 * @throws RangeException if data out of range
	 * @throws Exception For all other cases
	 *
	 **/
	public function __construct($profileId, $restaurantId) {
		try {
			$this->setProfileId($profileId);
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
	public function getProfileId() {
		return $this->profileId;
	}
   /**
	 * Mutator for Profile ID
	 *
	 * @param int $newProfileId
	 */
	public function setProfileId($newProfileID){
		// Base case: If the profile ID is null, this is a new liked restaurant without a MySQL assigned ID
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		// Verify the new restaurant ID
		$newRestaurantId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("Profile Id is not a valid integer"));
		}

		// Verify the new profile ID is positive
		if($newProfileId <= 0) {
			throw(new RangeException("Profile ID is not positive"));
		}

		// Convert and store the new Profile ID
		$this->profileId = intval($newProfileId);


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
		$parameters = array( "restaurantId" => $this->getRestaurntId(), "profileId" => $this->getProfileId());
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
		$query = "DELETE FROM likedrestaurant WHERE restaurantId = :restaurantId";
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
		$query = "UPDATE likedrestaurant SET restaurant Id = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("profileId" => $this->getPofileId(),"restaurantId" => $this->getRestaurantId());
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
	public static function getLikedRestaurantById(PDO &$pdo, $restaurantId) {
			// Sanitize the ID before searching
			$restaurantId = filter_var($restaurantId, FILTER_SANITIZE_NUMBER_INT);
			if($restaurantId === false) {
				throw(new PDOException("Restaurant ID is not an integer"));
			}
			if($restaurantId <= 0) {
				throw(new PDOException("Profile ID is not positive"));
			}

			// Create query template
			$query = "SELECT restaurantId, profileId, name FROM likedRestaurant WHERE restaurantId = :restaurantId";
			$statement = $pdo->prepare($query);

			// Bind restaurantId to placeholder
			$parameters = array("restaurantId" => Restaurant::getRestaurantId());
			$statement->execute($parameters);

			// Grab the likedrestaurant from MySQL
			try {
				$likedrestaurant = null;
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

			return ($likedrestaurant)

		// Bind address to placeholder
		$parameters = array("address" => Restaurant::getAddress());
		$statement->execute($parameters);




	/**
	 * Gets the likedrestaurant by profiele ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $profileID profile ID to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantByprofileId(PDO &$pdo, $profileId) {
		// Sanitize the ID before searching
		$profileId = trim($profileId);
		$profileId = filter_var($profileId, FILTER_SANITIZE_INT);
		if(empty($profileId)) {
			throw(new PDOException("profile ID is invalid"));
		}

		// Create query template
		$query = "SELECT restaurantId, profileId, name FROM likedrestaurant WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// Bind ID to placeholder
		$parameters = array("profileId" => LikedRestaurant::getProfileId());
		$statement->execute($parameters);

		// Grab the likedrestaurant from MySQL
		try {
			$likedrestaurant = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				// new LikedRestaurant($restaurantId, $profileId)
				$likedrestaurant = new LIkedRestaurant($row["restaurantId"], $row["profileId"]);
			}
		} catch(Exception $exception ) {
			// If the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		return ($likedrestaurant);
	}


	}
	/**
	 * Gets all restaurants
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @return SplFixedArray of LikedRestaurants found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getAllRestaurants(PDO &$pdo) {
		// Create query template
		$query = "SELECT restaurantId, profileId, name FROM likedrestaurant";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Build an array of likedrestaurants
		$restaurants = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				// new LikedRestaurant($restaurantId, $profileId)
				$likedRestaurant = new LikedRestaurant($row["restaurantId"], $row["profileId"]);
				$likedRestaurants[$likedRestaurants->key()] = $likedRestaurant;
				$likedRestaurants->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($likedRestaurants);
	}

}



