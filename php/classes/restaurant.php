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

}