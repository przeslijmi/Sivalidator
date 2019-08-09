<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;

/**
 * Methods for testing values against hint types.
 */
class TypeHinting
{

    /**
     * Tests if array consists of elements given type.
     *
     * ## Usage example
     * ```
     * $array = [ (new \stdClass()), (new \stdClass()), (new \stdClass()) ];
     * TypeHinting::isArrayOf($array, 'Namespace\Class');
     * ```
     *
     * @param array   $arrayOfObjects Array to be checked.
     * @param string  $className      Name of the class that is expected.
     * @param boolean $throw          Opt., true. Set to false to prevent throwing.
     *
     * @since  v1.0
     * @throws TypeHintingFailException If this is not array of given objects.
     * @return boolean
     */
    public static function isArrayOf(array $arrayOfObjects, string $className, bool $throw = true) : bool
    {

        // Lvd.
        $test = true;
        $isa  = '';

        // Test.
        foreach ($arrayOfObjects as $object) {

            if (is_object($object) === false) {
                $test = false;
                $isa  = gettype($object) . '[]';
                break;
            }

            if (is_a($object, $className) === false) {
                $test = false;
                $isa  = get_class($object) . '[]';
                break;
            }
        }

        // Throw.
        if ($throw === true && $test === false) {
            throw new TypeHintingFailException($className . '[]', $isa);
        }

        return $test;
    }

    /**
     * Tests if array consists of elements given type.
     *
     * ## Usage example
     * ```
     * $array = [ 'string1', 'string2', 'string3' ];
     * TypeHinting::isArrayOfStrings($array);
     * ```
     *
     * @param array   $arrayOfObjects Array to be checked.
     * @param boolean $acceptNulls    Opt., false. Set to true to also accept null values.
     * @param boolean $throw          Opt., true. Set to false to prevent throwing.
     *
     * @throws TypeHintingFailException If this is not array of strings.
     * @since  v1.0
     * @return boolean
     */
    public static function isArrayOfStrings(array $arrayOfObjects, bool $acceptNulls = false, bool $throw = true) : bool
    {

        // Lvd.
        $test = true;
        $isa  = '';

        // Test.
        foreach ($arrayOfObjects as $object) {

            $isNotString = ( is_string($object) === false );

            if ($acceptNulls === true) {
                $isNotNull = false;
            } else {
                $isNotNull = ( is_null($object) === false );
            }

            if ($isNotString === true && $isNotNull === true) {
                $test = false;
                $isa  = gettype($object) . '[]';
                break;
            }
        }

        // Throw.
        if ($throw === true && $test === false) {
            throw new TypeHintingFailException('string[]', $isa);
        }

        return $test;
    }

    /**
     * Tests if array consists of elements given type.
     *
     * ## Usage example
     * ```
     * $array = [ 5, true, 1.1, 'string' ];
     * TypeHinting::isArrayOfScalars($array);
     * ```
     *
     * @param array   $arrayOfObjects Array to be checked.
     * @param boolean $throw          Opt., true. Set to false to prevent throwing.
     *
     * @throws TypeHintingFailException If this is not array of scalars.
     * @since  v1.0
     * @return boolean
     */
    public static function isArrayOfScalars(array $arrayOfObjects, bool $throw = true) : bool
    {

        // Lvd.
        $test = true;
        $isa  = '';

        // Test.
        foreach ($arrayOfObjects as $object) {
            if (is_scalar($object) === false) {
                $test = false;
                $isa  = gettype($object) . '[]';
                break;
            }
        }

        // Throw.
        if ($throw === true && $test === false) {
            throw new TypeHintingFailException('scalar[]', $isa);
        }

        return $test;
    }
}
