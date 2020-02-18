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
        return (object)array_map(__FUNCTION__, $d);
    } else {
        // Return object
        return $d;
    }
}

/**
 * Validate a postal code.
 *
 * @param $value
 *   A postal code as string.
 * @param $country
 *   The country postal code format.
 * @return
 *   TRUE or FALSE if it validates.
 */
function valid_postal_code($value, $country = null) {
    $country_regex = array(
        'uk' => '/\\A\\b[A-Z]{1,2}[0-9][A-Z0-9]? [0-9][ABD-HJLNP-UW-Z]{2}\\b\\z/i',
        'ca' => '/\\A\\b[ABCEGHJKLMNPRSTVXY][0-9][A-Z][ ]?[0-9][A-Z][0-9]\\b\\z/i',
        'it' => '/^[0-9]{5}$/i',
        'de' => '/^[0-9]{5}$/i',
        'be' => '/^[1-9]{1}[0-9]{3}$/i',
        'us' => '/\\A\\b[0-9]{5}(?:-[0-9]{4})?\\b\\z/i',
        'default' => '/\\A\\b[0-9]{5}(?:-[0-9]{4})?\\b\\z/i' // Same as US.
    );

    if ($country !== null) {

        if (isset($country_regex[$country])) {
            return preg_match($country_regex[$country], $value);
        }

        return preg_match($country_regex['default'], $value);
    }

    $valid = true;
    foreach ($country_regex as $regex) {
        if (!preg_match($regex, $value)) {
            $valid = false;
        }
    }
    return $valid;
}

/**
 * @param \Illuminate\Database\Eloquent\Builder $builder
 * @return string
 */
function sql_with_bindings(\Illuminate\Database\Eloquent\Builder $builder) {
    return str_replace_array('?', array_map(function ($item) {
        return (is_string($item) && !($item instanceof \Carbon\Factory)) ? '"' . $item . '"' : $item;
    }, $builder->getBindings()), $builder->toSql());
}

/**
 * Alias for @see sql_with_bindings()
 *
 * @param \Illuminate\Database\Eloquent\Builder $builder
 * @return string
 */
function query_with_bindings(\Illuminate\Database\Eloquent\Builder $builder){
    return sql_with_bindings($builder);
}
