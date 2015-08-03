<?php

/**
 * This class contains data and functionality for a restaurant
 *
 * @author Skyler Rexroad
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
     * @var string $forkRating
     */
    private $forkRating;

    /**
     * Constructor for a restaurant
     *
     * @param int $restaurantId ID of the restaurant
     * @param int $googleId Google ID of the restaurant
     * @param int $facilityKey City ID of the restaurant
     * @param string
     * @param int $address address of the restaurant
     * @param int $phone phone number of the restaurant
     * @param int $forkRating TruFork rating of the restaurant
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
        // Verify the new Google ID
        $newGoogleId = trim($newGoogleId);
        $newGoogleId = filter_var($newGoogleId, FILTER_SANITIZE_STRING);
        if(empty($newGoogleId) === true) {
            throw(new InvalidArgumentException("Google Places restaurant ID is empty or insecure"));
        }

        // Verify that the Google ID will fit in the database
        if(strlen($newGoogleId) > 128) {
            throw(new RangeException("Google Places restaurant ID too long"));
        }

        // Store the new Google ID
        $this->googleId = $newGoogleId;
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
        // Verify the facility key is secure
        $newFacilityKey = trim($newFacilityKey);
        $newFacilityKey = filter_var($newFacilityKey, FILTER_SANITIZE_STRING);
        if(empty($newFacilityKey) == true) {
            throw(new InvalidArgumentException("Facility key is invalid or insecure"));
        }

        // Verify the facility key will fit in the database
        if(strlen($newFacilityKey) > 12) {
            throw(new RangeException("Facility key was too large"));
        }

        // Store the new facility key
        $this->facilityKey = $newFacilityKey;
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
        // Verify the name is secure
        $newName = trim($newName);
        $newName = filter_var($newName, FILTER_SANITIZE_STRING);
        if(empty($newName) === true) {
            throw(new InvalidArgumentException("Name is empty or insecure"));
        }

        // Verify the new name will fit in the database
        if(strlen($newName) > 128) {
            throw(new RangeException("Name too large"));
        }

        // Store the new address
        $this->name = $newName;
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
        // Verify the address is secure
        $newAddress = trim($newAddress);
        $newAddress = filter_var($newAddress, FILTER_SANITIZE_STRING);
        if(empty($newAddress) === true) {
            throw(new InvalidArgumentException("Address is empty or insecure"));
        }

        // Verify the new address will fit in the database
        if(strlen($newAddress) > 128) {
            throw(new RangeException("Address too large"));
        }

        // Store the new address
        $this->address = $newAddress;
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
        // Verify the phone number is secure
        $newPhone = trim($newPhone);
        $newPhone = filter_var($newPhone, FILTER_SANITIZE_STRING);
        if(empty($newPhone) == true) {
            throw(new InvalidArgumentException("Phone number is empty or insecure"));
        }

        // Verify the phone number will fit in the database
        if(strlen($newPhone) > 32) {
            throw(new RangeException("Phone number too large"));
        }

        // Store the new phone number
        $this->phone = $newPhone;
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
        // Verify the rating is secure
        $newForkRating = trim($newForkRating);
        $newForkRating = filter_var($newForkRating, FILTER_SANITIZE_STRING);
        if(empty($newForkRating) === true) {
            throw(new InvalidArgumentException("TruFork rating is empty or insecure"));
        }

        // Verify the rating will fit in the database
        if(strlen($newForkRating) > 32) {
            throw(new RangeException("Rating too large"));
        }

        // Store the new rating
        $this->forkRating = $newForkRating;
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
        $address = trim($address);
        $address = filter_var($address, FILTER_SANITIZE_STRING);
        if(empty($address)) {
            throw(new PDOException("Restaurant address is invalid"));
        }

        // Create query template
        $query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE address = :address";
        $statement = $pdo->prepare($query);

        // Bind address to placeholder
        $parameters = array("address" => Restaurant::getAddress());
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
        $phone = trim($phone);
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        if(empty($phone)) {
            throw(new PDOException("Restaurant phone number is invalid"));
        }

        // Create query template
        $query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE phone = :phone";
        $statement = $pdo->prepare($query);

        // Bind phone number to placeholder
        $parameters = array("phone" => Restaurant::getPhone());
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
        $forkRating = trim($forkRating);
        $forkRating = filter_var($forkRating, FILTER_SANITIZE_STRING);
        if(empty($forkRating)) {
            throw(new PDOException("TruFork rating is invalid"));
        }

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

        // Create query template
        $query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE facilityKey = :facilityKey";
        $statement = $pdo->prepare($query);

        // Bind key to placeholder
        $parameters = array("facilityKey" => Restaurant::getFacilityKey());
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
        $name = trim($name);
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        if(empty($name)) {
            throw(new PDOException("Restaurant name is invalid"));
        }

        // Create query template
        $query = "SELECT restaurantId, address, phone, forkRating, facilityKey, googleId, name FROM restaurant WHERE name = :name";
        $statement = $pdo->prepare($query);

        // Bind name to placeholder
        $parameters = array("name" => Restaurant::getName());
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