<?php

namespace Przeslijmi\Sivalidator;

use Przeslijmi\Sexceptions\Exceptions\RegexTestFailException;

/**
 * Methods for testing values against regex syntax.
 */
class Regex
{

    /**
     * Tests if string matches given regex (can return RegexTestFailException).
     *
     * ## Usage example
     * ```
     * Regex::ifMatches('Hello', '/^([a-z])$/');        // will throw RegexTestFailException
     * Regex::ifMatches('Hello', '/^([a-z])$/', false); // will return false
     * Regex::ifMatches('Hello', '/^([a-zA-Z])$/');     // will return true
     * ```
     *
     * @param  string $what
     * @param  string $regex
     * @param  bool   $throw (opt., true) Set to false to prevent throwing.
     * @throws RegexTestFailException
     * @since  v1.0
     * @return bool
     */
    public static function ifMatches(string $what, string $regex, bool $throw = true) : bool
    {

        // lvd & test
        $matches = (array)preg_grep($regex, [ $what ]);
        $test    = (bool)count($matches);

        // throw
        if ($throw === true && $test === false) {
            throw new RegexTestFailException($what, $regex);
        }

        return $test;
    }
}
