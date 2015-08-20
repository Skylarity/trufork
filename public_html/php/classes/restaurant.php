<?php

require_once(dirname(__DIR__) . "/helpers/filter.php");

/**
 * This class contains data and functionality for a restaurant
 *
 * @author Skyler Rexroad skyrex1095@gmail.com
 */
class Restaurant {

	/**
	 * ID for the restaurant; this is the primary key
	 * @var int $restaurantId
	 */
	private $restaurantId;

	/**
	 * Google's ID for the restaurant
	 * @var string $googleId
	 */
	private $googleId;

	/**
	 * City ID for the restaurant
	 * @var string $facilityKey
	 */
	private $facilityKey;

	/**
	 * Name of the restaurant
	 * @var string $name
	 */
	private $name;

	/**
	 * Address for the restaurant
	 * @var string $address
	 */
	private $address;

	/**
	 * Phone number for the restaurant
	 * @var string $phone
	 */
	private $phone;

	/**
	 * TruFork rating for the restaurant
	 * @var double $forkRating
	 */
	private $forkRating;

	/**
	 * Constructor for a restaurant
	 *
	 * @param int $restaurantId ID of the restaurant
	 * @param string $googleId Google ID of the restaurant
	 * @param string $facilityKey City ID of the restaurant
	 * @param string $name name of the restaurant
	 * @param string $address address of the restaurant
	 * @param string $phone phone number of the restaurant
	 * @param double $forkRating TruFork rating of the restaurant
	 * @throws InvalidArgumentException If the data is invalid
	 * @throws RangeException If the data is out of bounds
	 * @throws Exception For all other cases
	 */
	public function __construct($restaurantId, $googleId, $facilityKey, $name, $address, $phone, $forkRating) {
		try {
			$this->setRestaurantId($restaurantId);
			$this->setGoogleId($googleId);
			$this->setFacilityKey($facilityKey);
			$this->setName($name);
			$this->setAddress($address);
			$this->setPhone($phone);
			$this->setForkRating($forkRating);
		} catch(InvalidArgumentException $invalidArgument) {
			// Rethrow exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// Rethrow exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $e) {
			// Rethrow exception to the caller
			throw(new Exception($e->getMessage(), 0, $e));
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
		$this->restaurantId = Filter::filterInt($newRestaurantId, "Restaurant ID", true);
	}

	/**
	 * Accessor for Google ID
	 *
	 * @return string
	 */
	public function getGoogleId() {
		return $this->googleId;
	}

	/**
	 * Mutator for Google ID
	 *
	 * @param string $newGoogleId
	 */
	public function setGoogleId($newGoogleId) {
		$this->googleId = Filter::filterString($newGoogleId, "Google restaurant ID", 128);
	}

	/**
	 * Accessor for facility key
	 *
	 * @return string
	 */
	public function getFacilityKey() {
		return $this->facilityKey;
	}

	/**
	 * Mutator for facility key
	 *
	 * @param string $newFacilityKey
	 */
	public function setFacilityKey($newFacilityKey) {
		$this->facilityKey = Filter::filterString($newFacilityKey, "Facility key", 64);
	}

	/**
	 * Accessor for restaurant name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Mutator for restaurant name
	 *
	 * @param string $newName
	 */
	public function setName($newName) {
		$this->name = Filter::filterString($newName, "Restaurant name", 128);
	}

	/**
	 * Accessor for restaurant address
	 *
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Mutator for restaurant address
	 *
	 * @param string $newAddress
	 */
	public function setAddress($newAddress) {
		$this->address = Filter::filterString($newAddress, "Restaurant address", 128);
	}

	/**
	 * Accessor for restaurant phone number
	 *
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * Mutator for restaurant phone number
	 *
	 * @param string $newPhone
	 */
	public function setPhone($newPhone) {
		$this->phone = Filter::filterString($newPhone, "Restaurant phone number", 32);
	}

	/**
	 * Accessor for restaurant TruFork rating
	 *
	 * @return string
	 */
	public function getForkRating() {
		return $this->forkRating;
	}

	/**
	 * Mutator for restaurant TruFork rating
	 *
	 * @param string $newForkRating
	 */
	public function setForkRating($newForkRating) {
		$this->forkRating = Filter::filterInt($newForkRating, "TruFork rating");
	}

	/**
	 * Inserts this restaurant into MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function insert(PDO &$pdo) {
		// Make sure this is a new restaurant
		if($this->restaurantId !== null) {
			throw(new PDOException("Not a new restaurant"));
		}

		// Create query template
		$query = "INSERT INTO restaurant(address, phone, forkRating, facilityKey, googleId, name) VALUES(:address, :phone, :forkRating, :facilityKey, :googleId, :name)";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the template
		$parameters = array("address" => $this->getAddress(), "phone" => $this->getPhone(), "forkRating" => $this->getForkRating(), "facilityKey" => $this->getFacilityKey(), "googleId" => $this->getGoogleId(), "name" => $this->getName());
		$statement->execute($parameters);

		// Update the null restaurant ID with what MySQL has generated
		$this->setRestaurantId(intval($pdo->lastInsertId()));
	}

	/**
	 * Deletes this restaurant from MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function delete(PDO &$pdo) {
		// Make sure this restaurant already exists
		if($this->getRestaurantId() === null) {
			throw(new PDOException("Unable to delete a restaurant that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM restaurant WHERE restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("restaurantId" => $this->getRestaurantId());
		$statement->execute($parameters);
	}

	/**
	 * Updates this restaurant in MySQL
	 *
	 * @param PDO $pdo pointer to PDO connection , by reference
	 * @throws PDOException when MySQL related errors occur
	 */
	public function update(PDO &$pdo) {
		// Make sure this restaurant exists
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
	 * Gets the restaurant by restaurant ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $restaurantId restaurant ID to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantById(PDO &$pdo, $restaurantId) {
		// Sanitize the ID before searching
		try {
			$restaurantId = Filter::filterInt($restaurantId, "Restaurant ID");
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE restaurantId = :restaurantId";
		$statement = $pdo->prepare($query);

		// Bind restaurantId to placeholder
		$parameters = array("restaurantId" => $restaurantId);
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

	/**
	 * Gets the restaurant by address
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $address restaurant address to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantByAddress(PDO &$pdo, $address) {
		// Sanitize the address before searching
		try {
			$address = Filter::filterString($address, "Restaurant address", 128);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE address = :address";
		$statement = $pdo->prepare($query);

		// Bind address to placeholder
		$parameters = array("address" => $address);
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

	/**
	 * Gets the restaurant by phone
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $phone restaurant phone number to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantByPhone(PDO &$pdo, $phone) {
		// Sanitize the phone number before searching
		try {
			$phone = Filter::filterString($phone, "Restaurant phone number", 32);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE phone = :phone";
		$statement = $pdo->prepare($query);

		// Bind phone number to placeholder
		$parameters = array("phone" => $phone);
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

	/**
	 * Get restaurants by TruFork rating
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $forkRating TruFork rating to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantsByForkRating(PDO &$pdo, $forkRating) {
		// Sanitize the rating before searching
		try {
			$forkRating = Filter::filterInt($forkRating, "TruFork rating");
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE forkRating = :forkRating";
		$statement = $pdo->prepare($query);

		// Bind rating to placeholder
		$parameters = array("forkRating" => $forkRating);
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
		try {
			$facilityKey = Filter::filterString($facilityKey, "Facility Key", 64);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE facilityKey = :facilityKey";
		$statement = $pdo->prepare($query);

		// Bind key to placeholder
		$parameters = array("facilityKey" => $facilityKey);
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

	/**
	 * Gets the restaurant by Google ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $googleId Google ID to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantByGoogleId(PDO &$pdo, $googleId) {
		// Sanitize the ID before searching
		try {
			$googleId = Filter::filterString($googleId, "Google restaurant ID", 128);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE googleId = :googleId";
		$statement = $pdo->prepare($query);

		// Bind ID to placeholder
		$parameters = array("googleId" => $googleId);
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

	/**
	 * Gets the restaurant by name
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $name restaurant name to search for
	 * @return mixed Restaurant found or null if not found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getRestaurantByName(PDO &$pdo, $name) {
		// Sanitize the name before searching
		try {
			$name = Filter::filterString($name, "Name", 128);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE name = :name";
		$statement = $pdo->prepare($query);

		// Bind name to placeholder
		$parameters = array("name" => $name);
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