<?php

/**
 * This class contains methods to filter variables
 *
 * @author Skyler Rexroad
 */
class Filter {

	/**
	 * Filters an integer
	 *
	 * @param int $int integer to use
	 * @param string $name name of attribute to filter
	 * @param boolean $nullable whether the int can be null or not
	 * @return int|null new ID to use
	 */
	public static function filterInt($int, $name, $nullable = false) {
		// Base case: If the ID is null, this is a new object without a MySQL assigned ID
		if($nullable === true && $int === null) {
			return (null);
		}

		// Make sure the int is not null
		if($int === null) {
			throw(new InvalidArgumentException("$name cannot be null"));
		}

		// Verify the new int
		$int = filter_var($int, FILTER_VALIDATE_INT);
		if($int === false) {
			throw(new InvalidArgumentException("$name not a valid integer"));
		}

		// Verify the new int is positive
		if($int < 0) {
			throw(new RangeException("$name not positive"));
		}

		// Convert and return the new int
		return (intval($int));
	}

	/**
	 * Filters a double
	 *
	 * @param double $double double to use
	 * @param string $name name of attribute to filter
	 * @return double|null new ID to use
	 */
	public static function filterDouble($double, $name) {
		// Make sure the int is not null
		if($double === null) {
			throw(new InvalidArgumentException("$name cannot be null"));
		}

		// Verify the new int
		$double = filter_var($double, FILTER_VALIDATE_FLOAT);
		if($double === false) {
			throw(new InvalidArgumentException("$name not a valid double"));
		}

		// Convert and return the new int
		return (doubleval($double));
	}

	/**
	 * Filters a string
	 *
	 * @param string $string string to use
	 * @param string $name name of attribute to filter
	 * @param int $size maximum length of the string
	 * @return mixed new string to use
	 */
	public static function filterString($string, $name, $size = 0) {
		// Verify the new string
		$string = trim($string);
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(empty($string) === true) {
			throw(new InvalidArgumentException("$name is invalid"));
		}

		// Verify that the string will fit in the database
		if($size > 0 && strlen($string) > $size) {
			throw(new RangeException("$name is too long"));
		}

		// Return the new string
		return ($string);
	}

}