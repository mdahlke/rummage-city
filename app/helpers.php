<?php
/**
 * File: helpers.php
 * Date: 10/16/19
 * Time: 1:10 PM
 *
 * @package rummagecity.dev
 * @author Michael Dahlke <mdahlke@wisnet.com>
 */

/**
 * Recursively convert an array or multidimensional array to an object(s)
 *
 * @param $d
 * @return object
 */
function arrayToObject($d) {
	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return (object) array_map(__FUNCTION__, $d);
	}
	else {
		// Return object
		return $d;
	}
}
