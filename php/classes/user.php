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
	 * contructor for userId
	 *
	 * @param mixed $newUserId id of this user or null if a new user
	 * @param string $newSalt encrypted 64 hex pa
	 * @param string $newHash encrypted 128
	 * @throw rangeException if values are not === to 128
	 * */

	public function __construct($newUserId, $newSalt,$newHash){
		try {
			$this->setUserId($newUserId);
			$this->setSalt($newSalt);
			$this->SetHash($newHash);
		} catch(InvalidArgumentException($invalidArgument->getMessages()))
		}
	}

	}