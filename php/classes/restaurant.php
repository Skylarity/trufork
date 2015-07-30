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
	 * @var int $googleId
	 */
	private $googleId;

	/**
	 * City ID for the restaurant
	 * @var int $facilityKey
	 */
	private $facilityKey;

	/**
	 * Address for the restaurant
	 * @var int $address
	 */
	private $address;

	/**
	 * Phone number for the restaurant
	 * @var int $phone
	 */
	private $phone;

	/**
	 * TruFork rating for the restaurant
	 * @var int $forkRating
	 */
	private $forkRating;

	/**
	 * Constructor for a restaurant
	 *
	 * @param int $restaurantId ID of the restaurant
	 * @param int $googleId Google ID of the restaurant
	 * @param int $facilityKey City ID of the restaurant
	 * @param int $address address of the restaurant
	 * @param int $phone phone number of the restaurant
	 * @param int $forkRating TruFork rating of the restaurant
	 * @throws InvalidArgumentException If the data is invalid
	 * @throws RangeException If the data is out of bounds
	 * @throws Exception For all other cases
	 */
	public function __construct($restaurantId, $googleId, $facilityKey, $address, $phone, $forkRating) {
		try {
			$this->setRestaurantId($restaurantId);
			$this->setGoogleId($googleId);
			$this->setFacilityKey($facilityKey);
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
	 * @return int
	 */
	public function getRestaurantId() {
		return $this->restaurantId;
	}

	/**
	 * @param int $restaurantId
	 */
	public function setRestaurantId($restaurantId) {
		$this->restaurantId = $restaurantId;
	}

	/**
	 * @return int
	 */
	public function getGoogleId() {
		return $this->googleId;
	}

	/**
	 * @param int $googleId
	 */
	public function setGoogleId($googleId) {
		$this->googleId = $googleId;
	}

	/**
	 * @return int
	 */
	public function getFacilityKey() {
		return $this->facilityKey;
	}

	/**
	 * @param int $facilityKey
	 */
	public function setFacilityKey($facilityKey) {
		$this->facilityKey = $facilityKey;
	}

	/**
	 * @return int
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param int $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * @return int
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param int $phone
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @return int
	 */
	public function getForkRating() {
		return $this->forkRating;
	}

	/**
	 * @param int $forkRating
	 */
	public function setForkRating($forkRating) {
		$this->forkRating = $forkRating;
	}


}