<?php

/**
 * Version 1 of Autoloader
 * using __autoload bc it's a magic method and I want to memorialize Doug Henning
 * Not sure it groks the pathname; trying to get it to instantiate all of the files in that directory
 */
function __autoload($class) {
	include 'php/classes/' . $class . '.php';
}

/**
 * Version 2 of Autoloader
 * using spl_autoload_register bc php.net sez autoload may get deprecated
 * Made a named function bc trufork_autoloader sounds like a cool mid-80s industrial group
 * Doesn't seem to understand my syntax re path
 * Trying to get it to load all *.php files (sryNotSry for * tho)
 */
function trufork_autoloader($class) {
	require 'public_html/php/classes/' . $class . '.php';
}

spl_autoload_register('trufork_autoloader');

/**
 * Version 3 of Autoloader
 * mostly same as v2
 * using generic anon function instead of named function so The Man can't track us
 */

spl_autoload_register(function ($class) {
	require 'php/classes/' . $class . '.php';
});

