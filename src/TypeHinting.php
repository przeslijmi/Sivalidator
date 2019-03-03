<?php

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
     * TypeHinting::isArrayOf($array, 'Namespace/Class');
     *
     * @param  array  $arrayOfObjects Array to be checked.
     * @param  string $className      Name of the class that is expected.
     * @param  bool   $throw          (opt., true) Set to false to prevent throwing.
     * @throws TypeHintingFailException
     * @since  v1.0
     * @return bool
     */
    public static function isArrayOf(array $arrayOfObjects, string $className, bool $throw=true) : bool
    {

        // lvd
        $test = true;
        $isa = '';

        // test
        foreach ($arrayOfObjects as $object) {

            if (!is_object($object)) {
                $test = false;
                $isa = gettype($object) . '[]';
                break;
            }

            if (!is_a($object, $className)) {
                $test = false;
                $isa = get_class($object) . '[]';
                break;
            }
        }

        // throw
        if ($throw === true && $test === false) {
            throw new TypeHintingFailException($className . '[]', $isa);
        }

        return $test;
    }

    /**
     * Tests if array consists of elements given type.
     *
     * ## Usage example
     * TypeHinting::isArrayOfStrings($array);
     *
     * @param  array  $arrayOfObjects Array to be checked.
     * @param  bool   $acceptNulls    (opt., false) Set to true to also accept null values.
     * @param  bool   $throw          (opt., true) Set to false to prevent throwing.
     * @throws TypeHintingFailException
     * @since  v1.0
     * @return bool
     */
    public static function isArrayOfStrings(array $arrayOfObjects, bool $acceptNulls=false, bool $throw=true) : bool
    {

        // lvd
        $test = true;
        $isa = '';

        // test
        foreach ($arrayOfObjects as $object) {

            $isNotString = (!is_string($object));

            if ($acceptNulls === false) {
                $isNotNull = true;
            } else {
                $isNotNull = (!is_null($object));
            }

            if ($isNotString === true && $isNotNull === true) {
                $test = false;
                $isa = gettype($object) . '[]';
                break;
            }
        }

        // throw
        if ($throw === true && $test === false) {
            throw new TypeHintingFailException('string[]', $isa);
        }

        return $test;
    }

    /**
     * Tests if array consists of elements given type.
     *
     * ## Usage example
     * TypeHinting::isArrayOfStrings($array);
     *
     * @param  array  $arrayOfObjects Array to be checked.
     * @param  bool   $throw          (opt., true) Set to false to prevent throwing.
     * @throws TypeHintingFailException
     * @since  v1.0
     * @return bool
     */
    public static function isArrayOfScalars(array $arrayOfObjects, bool $throw=true) : bool
    {

        // lvd
        $test = true;
        $isa = '';

        // test
        foreach ($arrayOfObjects as $object) {
            if (!is_scalar($object)) {
                $test = false;
                $isa = gettype($object) . '[]';
                break;
            }
        }

        // throw
        if ($throw === true && $test === false) {
            throw new TypeHintingFailException('scalar[]', $isa);
        }

        return $test;
    }
}
