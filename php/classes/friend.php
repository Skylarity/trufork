<?php
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
		public function setFirstProfileId($newFirstProfiled) {
		// verify the first Profiled Id is valid
		$newFirstProfiledId = filter_var($newFirstProfileId, FILTER_VALIDATE_INT);
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
			$newSecondProfiledId = filter -var($newSecondProfileId, FILTER_VALIDATE - INT);
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
			// verify the data friended is valid
			$newDataFriend = filter_var($newDateFriended, FILTER_SANITIZE_STRIPING);
			if ($newDateFriended === false) {
								throw(newUnexpectedValueExceptin("data Friended is not a valid data")
	}
	// store the date Friended
	$this ->dateFriended = $newDateFriended;

	}

	}