<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use Przeslijmi\Sexceptions\Exceptions\RegexTestFailException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;

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
     * @since  v1.0
     * @throws RegexTestFailException When RegEx will return false, and throwing is 'On'.
     * @throws ParamWrosynException   When second param (regex) is corrupted.
     * @throws ParamWrosynException   When second param (regex) is too short (<=2).
     * @return boolean
     */
    public static function ifMatches(string $what, string $regex, bool $throw = true) : bool
    {

        // Check first and last letter of $regex.
        $testRegex = rtrim($regex, 'imsxuADU');
        if (substr($testRegex, 0, 1) !== '/' || substr($testRegex, -1) !== '/') {
            $hint  = 'Regex has to start and and with a slash.';
            $hint .= 'Haven\'t you replaced order of params in method call?';
            throw (new ParamWrosynException('regex', $regex))->addInfo('hint', $hint);
        }

        // Check regex min length.
        if (strlen($regex) < 2) {
            throw (new ParamWrosynException('regex', $regex))
                ->addInfo('hint', 'Regex has to be at least 2 char length.');
        }

        // Lvd & test.
        $matches = (array) preg_grep($regex, [ $what ]);
        $test    = (bool) count($matches);

        // Throw.
        if ($throw === true && $test === false) {
            throw new RegexTestFailException($what, $regex);
        }

        return $test;
    }

    /**
     * Tests if string NOT matches given regex (can return RegexTestFailException).
     *
     * ## Usage example
     * ```
     * RegEx::ifNotMatches('Hello', '/^([a-z]+)$/');           // will return true
     * RegEx::ifNotMatches('Hello', '/^([a-z]+)$/', false);    // will return true
     * RegEx::ifNotMatches('Hello', '/^([a-zA-Z]+)$/');        // will throw RegexTestFailException
     * RegEx::ifNotMatches('Hello', '/^([a-zA-Z]+)$/', false); // will return false
     * ```
     *
     * @param string  $what  String to search in.
     * @param string  $regex Regular expression.
     * @param boolean $throw Opt., true. Set to false to prevent throwing.
     *
     * @throws   RegexTestFailException When RegEx will return true, and throwing is 'On'.
     * @rethrows ParamWrosynException   When second param (regex) is corrupted.
     * @since    v1.0
     * @return   boolean
     */
    public static function ifNotMatches(string $what, string $regex, bool $throw = true) : bool
    {

        // Get test.
        $test = self::ifMatches($what, $regex, false);

        // Reverse it.
        $test = ( ! $test );

        // Serve excception.
        if ($throw === true && $test === false) {
            throw new RegexTestFailException($what, 'OPPOSITE TO: ' . $regex);
        }

        return $test;
    }
}
