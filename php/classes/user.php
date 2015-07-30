<?php

/**
 * Created by PhpStorm.
 * User: Kenneth Anthony
 * Date: 7/29/2015
 * Time: 9:44 PM
 */
class User {
	/**
	 * id for this user is a primary key
	 * @var int $userId
	**/
	private $userId;
	/**
	 * salt encryption for userId
	 * @var int $userId
	 **/
	private $salt;
	/**
	 * hash encryption for userId
	 * @var string $hash password
	 * */
	private $hash;

	/**
	 * constructor for userId
	 *
	 * @param mixed $newUserId id of this user or null if a new user
	 * @param string $newSalt password encrypted 64 hex pa
	 * @param string $newHash $password encrypted 128 using pbkdf2 algorithm
	 * @throw InvalidArgumentException if data types are not valid
	 * @throw rangeException if values are not === to 128
	 * @throw Exception if some other exception is thrown
	 * @throws Exception
	 */

	public function __construct($newUserId, $newSalt, $newHash = null) {
		try {
			$this->setUserId($newUserId);
			$this->setSalt($newSalt);
			$this->setHash($newHash);
		} catch(InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the call
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to caller
			throw(new RangeException($range->getMessage(), 0, $range));
			// rethrow the exception to caller
		} catch(Exception $exception) {
			//rethrow generic exception
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 *accessor method for user id
	 *
	 *@return mixed value
	 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * mutator method for user id
	 * @param $newUserId new value for userId
	 * @throws InvalidArgumentException if $newUserId is not an integer
	 */


	private function setUserId($newUserId) {
		//base: case: if the user id is null, this a new user id without mySQL assigned id (yet)
		if($newUserId === null) {
			$this->userId = null;
			return;
		}

	}

	/**
	 * @param $newSalt
	 */
	private function setSalt($newSalt) {
	}

	/**
	 * @param $newHash
	 */
	private function setHash($newHash) {
	}
}


