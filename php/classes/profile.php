<?php

/**
 * Created by PhpStorm.
 * User: Kenneth Anthony
 * Date: 8/2/2015
 * Time: 8:24 PM
 */
class Profile {
	/**
	 * id for TruFork; this is a primary key
	 * @var   int $profileId
	 */
	private $profileId;

	/**
	 * user id that saved end user uses to vote
	 * @var $userId ; this is a primary key
	 **/
	private $userId;

	/** email is an attribute
	 * @var $email ; this is stored in profile settings
	 **/
	private $email;

	/**
	 * accessor method for profileId
	 *
	 * @return int mixed value of profileId
	 */
	public function getProfileId() {
		return $this->profileId;
	}


	/**
	 * Constructor method for user class
	 *
	 * @param mixed $newProfileId id for this class
	 * @param int $userId id for profile id
	 * @param $newEmailId
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 * @throws Exception if some other exception is thrown
	 *
	 */

	public function __construct($newProfileId, $userId, $newEmailId, = null){
		try{
			$this->setProfileId($newProfileId);
			$this->setUserId($userId);
			$this->setEmail($newEmailId);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow range exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			// rethrow generic exception
			throw(new Exception($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * mutator method for profile id
	 *
	 * @param int $profileId
	 * @throws InvalidArgumentException if $profileId is not integer
	 * @throws RangeException if $newProfileId is not positive
	 */
	public function setProfileId($newProfileId) {
		// base case: if the profile id is null, this a new profile id without a mySQL assigned id (yet)
		if($newProfileId === null) {
			$this->profileId = $ProfileId = null;
			return;
		}

			//verify the profile id is valid
		$newprofile = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
		throw(new InvalidArgumentException ("profile id is not a valid integer"));
		}

		// verify if the profile id is valid
		if($newProfileId <= 0) {
			throw(new RangeException("profile id is not positive"));
		}

		//convert and store the profile Id
		$this->profileId = interval($newProfileId);
		}

	/**
	 * @return mixed
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * mutator method for user id
	 *
	 * @param mixed $userId
	 * @throws InvalidArgumentException if $userId is not a integer
	 * @throws RangeException if $UserId is not positive
	 */

	public function setUserId($userId) {
		// verify the profile id is valid
		$userId = filter_var($userId, FILTER_VALIDATE_INT);
		if($userId === false) {
			throw(new InvalidArgumentException("user id is not a valid integer"));
		}

		// verify the user id is positive
		If($userId <= 0) {
			throw(new RangeException("user id is not positive"));

		//convert and store user id
		$this->$userId = intval($userId);
		}
	}

	/**
	 * accessor method for email
	 *
	 * @return email vaule
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param email $email
	 * @throws InvalidArgumentException if $email is invalid
	 * @throws RangeException
	 */
	public function setEmail($email) {
		$this->email = $email;
		// Need to Ask Dylan, about this. I remember that he sends an email validation
		// not a FILTER_VALIDATE_EMAIL
	}



}