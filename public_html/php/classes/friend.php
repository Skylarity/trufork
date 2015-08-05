<?php

/**
 * This Friend is an example of date collected and store about a profile for  the
 * capstone about restaurants
 * @author H Perez <hperezperez.cnm.edu>
 **/


	class Friend {
		/**
		*  id for the firstProfiled who is generate ; this is a foreign key
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
	} catch(UnexpectedValueException $exception){
			//rethrow to the caller
				throw(new UnexpectedValueException("Unable to construct Friend", 0, $exception));
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
		if($newFirstProfileId ===false){
					throw(new UnexpectedValueException("first profile is not valid integer"));
		}
		// convert and store the firstprofiled id
		$this -> firstProfileId = intval($newFirstProfileId);
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
					$this->dateFriended = new DateFriended();
					return;
		}
	// store the date Friended
		try {
					$newDateFriended = validateDate($newDateFriended);
		} catch(InvalidArgumentException $invalidArgument ){
					throw(new InvalidArgumentException($invalidArgument->getMessage(),
						0, $invalidArgument));
		} catch(RangeException $range) {
							throw(new RangeException($range->getMessage(), 0,$range));
		}
	$this ->dateFriended = $newDateFriended;
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
 	}