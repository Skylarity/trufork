<?php
//grab the date parsing function
require_once(dirname(__DIR__) . "/traits/validatedate.php");

/**
 * This Friend is an example of date collected and store  for  trufork
 *
 * @author H Perez <hperezperez.cnm.edu>
 */


	class Friend {
		/**
		*  id for the firstProfile who is generate ; this is a foreign key
		**/
		private $firstProfileId;
		/**
		*  id for the secondProfiled who is generate; this is a foreign key
		**/
		private $secondProfileId;
		/**
		* date Friended is date that get friended
		**/
		private $dateFriended;


		use validateDate;


		/**
		 * constructor for this Friend
		 *
		 * @param int $newFirstProfile new firstProfile id
		 * @param int $newSecondrofile new secondProfile id
		 * @param date $newDateFriended new dateFriended
		 * @throws UnexpectedValueException if any of the parameters are invalid
		 **/
		public function __construct($newFirstProfileId, $newSecondProfileId, $newDateFriended){
				try{
						$this->setFirstProfileId($newFirstProfileId);
						$this->setSecondProfileId($newSecondProfileId);
	 					$this->setDateFriended($newDateFriended);
	}   catch(InvalidArgumentException $invalidArgument) {
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
		* accesor method for firstProfile id
		*@return int value for firstProfile id
		**/
		public function getFirstProfileId(){
					return($this->firstProfileId);
		}
		/**
		* mutator method for first profileId
		*
		*@param int $newFirstProfileId new value  of firstProfile id
		*@throws  UnexpectedValueException if $new FirstprofileId is not integer
		**/
		public function setFirstProfileId($newFirstProfileId) {
		// verify the first Profiled Id is valid
		$newFirstProfileId = filter_var($newFirstProfileId, FILTER_VALIDATE_INT);
		if($newFirstProfileId === false){
					throw(new UnexpectedValueException("first profile is not valid integer"));
		}
		 //verify the firstProfile Id is positive
		 if ($newFirstProfileId <= 0){
		 			throw(new RangeException(" firstProfileId is not positive"));
		// convert and store the firstprofile id
		 }
			$this->firstProfileId = intval($newFirstProfileId);
		}
		/**
		*		accesor method for secondProfileId
		*
		*@return int value of secondProfile id
		**/
		public function getSecondProfileId() {
								return($this->secondProfileId);
		}
		/**
		* mutator method for secondProfiledId
		*@ para int $newSecondProfiledID new value of secondProfileId
		*@throws UnexpectedVAlueException if $newSecondProfiledID is not a  integer
		**/
		public function setSecondProfileId($newSecondProfileId) {
			//verify the secondProfile id is valid
			$newSecondProfileId = filter_var($newSecondProfileId, FILTER_VALIDATE_INT);
						if($newSecondProfileId === false) {
							throw(new UnexpectedValueException("secondProfiledId id is not a valid integer"));
						}
		//convert and store the secondProfiled id
	$this->secondProfileId = intval($newSecondProfileId);
	}
	/**
	*accssor method for dateFriended
	*
	*@return date value of dateFriended
	**/
	public function getDateFriended() {
				return($this->dateFriended);
	}
	/**
	* mutator method for dateFriended
	*
	*@param date $newDateFriended new value of dateFriended
	*
	*@throws UnexpectedValueException if $newDateProvided is not valid
	**/
	public function setDateFriended($newDateFriended) {
		// base case : if the date is null, use the current date and time
		if($newDateFriended === null) {
					$this->dateFriended = $newDateFriended;
					return;
		}
	// store the date Friended
		try {
					$newDateFriended = validateDate::validateDate($newDateFriended);
		} catch(InvalidArgumentException $invalidArgument ){
					throw(new InvalidArgumentException($invalidArgument->getMessage(),
						0, $invalidArgument));
		} catch(RangeException $range) {
							throw(new RangeException($range->getMessage(), 0,$range));
		}
	$this->dateFriended = $newDateFriended;
	}
/** toString() magic method
 *
 * @return string HTML formatted Friend
 **/
       public function __tosString() {
					//create an HTML formatted friend
				$html="<p>FirstProfile id". $this->firstProfileId."<br/>"
						."SecondProfile id:" .$this->secondProfileId ."<br/>"
						."DateFriended:" .$this->dateFriended
						."<p>";
				return($html);
}
		/** inserts friend into DataBase
		 *@param PDO $pdo pointer to pdo connection by reference
		 *@throws PDOException in the event of mySQL errors
		 **/
		public function insert (PDO &$pdo) {
			$query = "INSERT INTO friend(firstProfileId, secondProfileId, dateFriended) VALUES (:firstProfileId,:secondProfileId,:dateFriended)";
			$statement = $pdo->prepare($query);

			$formattedDateFriended = $this->dateFriended->format("Y-m-d H:i:s");
			$parameters = array("dateFriended" => $formattedDateFriended,"firstProfileId" => $this->firstProfileId,"secondProfileId" => $this->secondProfileId);
			$statement->execute($parameters);

			$this->setFirstProfileId(intval($pdo->lastInsertId()));
		}
		/**
		 * Deletes friend from DB
		 *
		 * @param PDO $pdo pointer to PDO connection by reference
		 * @throws PDOException when MySQL related errors occur
		 **/
		public function delete (PDO &$pdo) {
			if($this->firstProfileId === null) {
				throw(new PDOException("cannot delete a friend that does not exist"));
			}
			$query = "DELETE FROM friend WHERE firstProfileId = :firstProfileId AND secondProfileId = :secondProfileId";
			$statement = $pdo->prepare($query);

			$parameters = array("firstProfileId" => $this->firstProfileId,"secondProfileId" =>$this->secondProfileId);
			 $statement->execute($parameters);
		}
		/** updates friend in DB
		 *
		 * @param PDO $pdo pointer to PDO connection by reference
		 * @throws PDOException when MySQL related errors occur
		 **/
		public function update (PDO &$pdo) {


			if ($this->firstProfileId === null && $this->secondProfileId === null) {
				throw(new PDOException("cannot update a friend that does not exist"));
			}
			$query = "UPDATE friend SET firstProfileId = :firstProfileId, secondProfileId = :secondProfileId, dateFriended = :dateFriended
			WHERE firstProfileId = :firstProfileId AND secondProfileId = :secondProfileId AND dateFriended = :dateFriended ";
			$statement = $pdo->prepare($query);

			$formattedDateFriended = $this->dateFriended->format("Y-m-d H:i:s");
			$parameters = array("dateFriended" => $formattedDateFriended,"firstProfileId" => $this->firstProfileId,"secondProfileId" => $this->secondProfileId);
			$statement->execute($parameters);
		 }

			/** gets the friend by firstProfileId or secondProfileId
			 * @param PDO $pdo pointer to PDO connection by reference
			 * @param int $firstprofileId comment firstProfileId to search for
  			 * @return mixed profile found or null if not found
			 * @throws PDOException when mySQL related errors occur
			 **/
			public static function getFriendByFriendId(PDO &$pdo, $firstProfileId, $secondProfileId) {
					// Sanitize the ID before searching
					$firstProfileId = filter_var($firstProfileId, FILTER_SANITIZE_NUMBER_INT);
					if($firstProfileId === false) {
						throw(new PDOException(" firstProfile Id is not an integer"));
					}
					if($firstProfileId <= 0) {
						throw(new PDOException("firstProfile ID is not positive"));
					}
					//Sanitize the secodProfileID
				$secondProfileId = filter_var($secondProfileId, FILTER_SANITIZE_NUMBER_INT);
				if($secondProfileId === false) {
					throw(new PDOException(" secondProfile Id is not an integer"));
				}
				if($secondProfileId <= 0) {
					throw(new PDOException("secondProfile ID is not positive"));
				}

				// Create query template
					$query = "SELECT firstProfileId, secondProfileId, dateFriended FROM friend WHERE firstProfileId = :firstProfileId
					OR secondProfileId = :secondProfileId ";
					$statement = $pdo->prepare($query);

					// Bind firstProfileId  and secondProfileId to placeholder
					$parameters = array("firstProfileId" => $firstProfileId,"secondProfileId"=> $secondProfileId);
					$statement->execute($parameters);


					// Grab the friend from MySQL
					try {
						$friend = null;
						$statement->setFetchMode(PDO::FETCH_ASSOC);
						$row = $statement->fetch();

						if($row !== false) {
							// new Friend($firstProfileId, $secondProfileId, $dataFriended)
							$friend = new Friend($row["firstProfileId"], $row["secondProfileId"], $row["dateFriended"]);
						}
					} catch(Exception $e) {
						// If the row couldn't be converted, rethrow it
						throw(new PDOException($e->getMessage(), 0, $e));
					}

					return ($friend);
				}


	}







