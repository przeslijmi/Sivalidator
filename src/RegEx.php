<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use Przeslijmi\Sexceptions\Exceptions\RegexTestFailException;

/**
 * Methods for testing values against regex syntax.
 */
class RegEx
{

    /**
     * Tests if string matches given regex (can return RegexTestFailException).
     *
     * ## Usage example
     * ```
     * RegEx::ifMatches('Hello', '/^([a-z]+)$/');        // will throw RegexTestFailException
     * RegEx::ifMatches('Hello', '/^([a-z]+)$/', false); // will return false
     * RegEx::ifMatches('Hello', '/^([a-zA-Z]+)$/');     // will return true
     * ```
     *
     * @param string  $what  String to search in.
     * @param string  $regex Regular expression.
     * @param boolean $throw Opt., true. Set to false to prevent throwing.
     *
     * @throws RegexTestFailException When RegEx will return false, and throwing is 'On'.
     * @since  v1.0
     * @return boolean
     */
    public static function ifMatches(string $what, string $regex, bool $throw = true) : bool
    {

        // Lvd & test.
        $matches = (array) preg_grep($regex, [ $what ]);
        $test    = (bool) count($matches);

        // Throw.
        if ($throw === true && $test === false) {
            throw new RegexTestFailException($what, $regex);
        }

        return $test;
    }
}
