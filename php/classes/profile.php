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
	 * @var	int $profileId
	 */private $profileId;

	/**
	 * user id that saved end user uses to vote
	 * @var $userId; this is a primary key
	 **/
	private $userId;

	/** email is an attribute
	 *@var emailId; this is stored in profile settings
	 **/
	private $email;

	/**
	 * accessor method for tweet id
	 * @return int
	 */
	public function getProfileId() {
		return $this->profileId;
	}

	/**
	 * @param int $profileId
	 */
	public function setProfileId($profileId) {
		$this->profileId = $profileId;
	}

	/**
	 * @return mixed
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @param mixed $userId
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}

	/**
	 * @return emailId
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param emailId $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}



}