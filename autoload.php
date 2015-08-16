<?php

/**
 * Version 1 of Autoloader
 * using __autoload bc it's a magic method and I want to memorialize Doug Henning
 * Not sure it groks the pathname; trying to get it to instantiate all of the files in that directory
 * @param string $class name of class to load
 */
function __autoload($class) {
	require 'php/classes/' . $class . '.php';
}

/**
 * Version 2 of Autoloader
 * using spl_autoload_register bc php.net sez autoload may get deprecated
 * Made a named function bc trufork_autoloader sounds like a cool mid-80s industrial group
 * Doesn't seem to understand my syntax re path
 * Trying to get it to load all *.php files (sryNotSry for * tho)
 * @param string $class name of class to load
 *
 */
function trufork_autoloader($class) {
	require 'public_html/php/classes/' . $class . '.php';
}

spl_autoload_register('trufork_autoloader');

/**
 * Version 3 of Autoloader
 * mostly same as v2
 * using generic anon function instead of named function so The Man can't track us
 * @param string $class name of class to load
 */
spl_autoload_register(function ($class) {
	require 'php/classes/' . $class . '.php';
});

/**
 * Version 4 of Autoloader
 * stolen from Skyler
 * sin verguenza
 */

spl_autoload_register("Autoloader::classLoader");

class Autoloader {
	/**
	 * This function autoloads classes if they exist
	 *
	 * @param string $className name of class to load
	 * @throws Exception unable to load class
	 */
	public static function classLoader($className) {
		$className = strtolower($className);
		if(is_readable(__DIR__ . "/$className.php")) {
			require_once(__DIR__ . "/$className.php");
		} else {
			throw(new Exception("Unable to load $className.php"));
		}
	}

}
