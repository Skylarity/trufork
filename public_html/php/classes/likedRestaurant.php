<?php

/**
 * Creation of liked restaurant class for trufork
 *
 * This script is designed to create liked restaurant for trufork.
 *
 * @author  Humberto Perez <hperezperez@cnm.edu>
<<<<<<< Updated upstream
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
	 * @var int foreign key
	 **/
	private $profileId;

	/** constructor method for likeRestaurant
	 * @param int $profileId
	 * @param int $restaurantId
	 * @throws InvalidArgumentException if the data is invalid
	 * @throws RangeException if data out of range
	 * @throws Exception For all other cases
	 *
	 **/
	public function __construct($profileId, $restaurantId) {
		try {
			$this->setProfileId($profileId);
			$this->setRestaurantId($restaurantId);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
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
	public function setProfileId($newProfileId) {
		// Base case: If the profile ID is null, this is a new liked restaurant without a MySQL assigned ID
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		// Verify the new restaurant ID
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("Profile Id is not a valid integer"));
		}

		// Verify the new profile ID is positive
		if($newProfileId <= 0) {
			throw(new RangeException("Profile ID is not positive"));
		}

		// Convert and store the new Profile ID
		$this->profileId = intval($newProfileId);
	}


	/**
	 * Inserts this liked restaurant into MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function insert(PDO &$pdo) {
		// Make sure this is a new likedrestaurant
		if($this->restaurantId !== null) {
			throw(new PDOException("Not a new liked restaurant"));
		}

		// Create query template
		$query = "INSERT INTO likedRestaurant( restaurantId, profileId ) VALUES( :restaurantID, :profileId)";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the template
		$parameters = array("restaurantId" => $this->getRestaurantId(), "profileId" => $this->getProfileId());
		$statement->execute($parameters);

		// Update the null restaurant ID with what MySQL has generated
		$this->setRestaurantId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this liked restaurant from MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this liked restaurant already exists
		if($this->getRestaurantId() === null) {
			throw(new PDOException("Unable to delete a liked restaurant that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM likedRestaurant WHERE restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("restaurantId" => $this->getRestaurantId());
		$statement->execute($parameters);
	}

	/**
	 * Updates this liked restaurant in MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function update(PDO &$pdo) {
		// Make sure this liked restaurant exists
		if($this->getRestaurantId() === null) {
			throw(new PDOException("Unable to update a restaurant that does not exist"));
		}

		// Create query template
		$query = "UPDATE likedRestaurant SET restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("profileId" => $this->getProfileId(), "restaurantId" => $this->getRestaurantId());
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
		$query = "SELECT restaurantId, profileId FROM likedRestaurant WHERE restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind restaurantId to placeholder
		$parameters = array("restaurantId" => LikedRestaurant::getRestaurantId());
		$statement->execute($parameters);

		// Grab the liked restaurant from MySQL
		try {
			$likedRestaurant = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				// new LIkedRestaurant($restaurantId, $profileId)
				$likedRestaurant = new LikedRestaurant($row["restaurantId"], $row["profileId"]);
			}
		} catch(Exception $exception) {
			// If the row couldn't be converted, rethrow i
		}


return ($likedRestaurant);

}

	/**
	 * Gets all liked restaurants
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @return SplFixedArray of LikedRestaurants found
	 * @throws PDOException when MySQL related errors occur
	*/
	public static function getAllLikedRestaurants(PDO &$pdo) {
		// Create query template
		$query = "SELECT restaurantId, profileId FROM likedRestaurant";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Build an array of liked restaurants
		$likedRestaurants = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				// new LikedRestaurant($restaurantId, $profileId)
				$likedRestaurant = new LikedRestaurant($row["restaurantId"], $row["profileId"]);
				$likedRestaurants[$likedRestaurants->key()] = $likedRestaurants;
				$likedRestaurants->next();
			} catch(Exception $e) {
				// If the row couldn't be converted, rethrow it
				throw(new PDOException($e->getMessage(), 0, $e));
			}
		}

		return ($likedRestaurants);
	}


	public static function getAllLikedRestaurant(PDO &$pdo) {
			 // Create query template
			 $query = "SELECT restaurantId, profileId  FROM likedRestaurant";
			 $statement = $pdo->prepare($query);
			 $statement->execute();

			 // Build an array of likedrestaurants
			 $restaurants = new SplFixedArray($statement->rowCount());
			 $statement->setFetchMode(PDO::FETCH_ASSOC);
			 while(($row = $statement->fetch()) !== false) {
				 try {
					 // new LikedRestaurant($restaurantId, $profileId)
					 $likedRestaurant = new LikedRestaurant($row["restaurantId"], $row["profileId"]);
					 $likedRestaurants[$likedRestaurants->key()] = $likedRestaurant
					 ;
					 $likedRestaurants->next();
				 } catch(Exception $e) {
					 // If the row couldn't be converted, rethrow it
					 throw(new PDOException($e->getMessage(), 0, $e));
				 }
			 }

			 return ($likedRestaurants);
		 }



 }


