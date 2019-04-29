<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;

/**
 * Methods for testing values against type hinting class.
 */
final class TypeHintingTest extends TestCase
{

    /**
     * Test if "isArrayOf" returns true on positive values.
     *
     * @return void
     */
    public function testIfIsArrayOfRtot() : void
    {

        $array = [
            ( new \stdClass() ),
            ( new \stdClass() ),
            ( new \stdClass() ),
        ];

        $this->assertTrue(TypeHinting::isArrayOf($array, 'stdClass'));
    }

    /**
     * Test if "isArrayOf" returns false on negative values.
     *
     * @return void
     */
    public function testIfIsArrayOfRfof1() : void
    {

        $array = [
            ( new \stdClass() ),
            ( new \stdClass() ),
            ( new \stdClass() ),
        ];

        $this->assertFalse(TypeHinting::isArrayOf($array, 'Other\Name', false));
    }

    /**
     * Test if "isArrayOf" returns false on negative values.
     *
     * @return void
     */
    public function testIfIsArrayOfRfof2() : void
    {

        $array = [
            'string',
            'string',
            'string',
        ];

        $this->assertFalse(TypeHinting::isArrayOf($array, 'Other\Name', false));
    }

    /**
     * Test if "isArrayOf" throws.
     *
     * @return void
     */
    public function testIfIsArrayOfThrows() : void
    {

        $this->expectException(TypeHintingFailException::class);

        $array = [
            ( new \stdClass() ),
            ( new \stdClass() ),
            ( new \stdClass() ),
        ];

        $this->assertFalse(TypeHinting::isArrayOf($array, 'Other\Name'));
    }

    /**
     * Test if "isArrayOfStrings" returns true on positive values.
     *
     * @return void
     */
    public function testIfIsArrayOfStringsRtot() : void
    {

        $array = [ 'string1', 'string2', 'string3' ];

        $this->assertTrue(TypeHinting::isArrayOfStrings($array));
    }

    /**
     * Test if "isArrayOfStrings" returns true on positive values (including nulls).
     *
     * @return void
     */
    public function testIfIsArrayOfStringsRtotWithNulls() : void
    {

        $array = [ 'string1', 'string2', 'string3', null ];

        $this->assertTrue(TypeHinting::isArrayOfStrings($array, true));
    }

    /**
     * Test if "isArrayOfStrings" returns false on negative values.
     *
     * @return void
     */
    public function testIfIsArrayOfStringsRfof() : void
    {

        $array = [ 'string1', 3, true ];

        $this->assertFalse(TypeHinting::isArrayOfStrings($array, false, false));
    }

    /**
     * Test if "isArrayOfStrings" returns true on positive values (including nulls).
     *
     * @return void
     */
    public function testIfIsArrayOfStringsRfofWithNulls() : void
    {

        $array = [ 'string1', 'string2', 'string3', null ];

        $this->assertTrue(TypeHinting::isArrayOfStrings($array, false, false));
    }

    /**
     * Test if "isArrayOfStrings" throws.
     *
     * @return void
     */
    public function testIfIsArrayOfStringsThrows() : void
    {

        $this->expectException(TypeHintingFailException::class);

        $array = [ 'string1', 3, true ];

        $this->assertFalse(TypeHinting::isArrayOfStrings($array));
    }

    /**
     * Test if "isArrayOfScalars" returns true on positive values.
     *
     * @return void
     */
    public function testIfIsArrayOfScalarsRtot() : void
    {

        $array = [ 5, true, 1.1, 'string' ];

        $this->assertTrue(TypeHinting::isArrayOfScalars($array));
    }

    /**
     * Test if "isArrayOfScalars" returns false on negative values.
     *
     * @return void
     */
    public function testIfIsArrayOfScalarsRfof() : void
    {

        $array = [ [ 5 , true ], 1.1, 'string' ];

        $this->assertFalse(TypeHinting::isArrayOfScalars($array, false));
    }

    /**
     * Test if "isArrayOfScalars" throws.
     *
     * @return void
     */
    public function testIfIsArrayOfScalarsThrows() : void
    {

        $this->expectException(TypeHintingFailException::class);

        $array = [ [ 5 , true ], 1.1, 'string' ];

        $this->assertFalse(TypeHinting::isArrayOfScalars($array));
    }
}
