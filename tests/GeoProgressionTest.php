<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\RegexTestFailException;
use Przeslijmi\Sivalidator\RegEx;

/**
 * Methods for testing Geo Progression.
 */
final class GeoProgressionTest extends TestCase
{

    /**
     * Test if proper search in progression asserts true.
     *
     * @return void
     */
    public function testIfProperReturnsTrue() : void
    {

        $this->assertTrue(GeoProgression::has(1, 129));
    }

    /**
     * Test if inproper search in progression asserts false.
     *
     * @return void
     */
    public function testIfInproperReturnsFalse() : void
    {

        $this->assertFalse(GeoProgression::has(2, 129));
    }

    /**
     * Test if both shorutcuts will be used for acceleration.
     *
     * @return void
     */
    public function testIfShortcutsAreUsed() : void
    {

        $this->assertEquals(0, GeoProgression::highestNumber(0));
        $this->assertEquals(0, GeoProgression::highestNumberValue(0));
    }

    /**
     * Test if proper searches are made on changed ratio.
     *
     * @return void
     */
    public function testIfChangedRatioWorks() : void
    {

        $this->assertTrue(GeoProgression::has(1, 1, 3));
        $this->assertTrue(GeoProgression::has(3, 3, 3));
        $this->assertTrue(GeoProgression::has(1, 4, 3));
        $this->assertTrue(GeoProgression::has(3, 4, 3));

        $this->assertFalse(GeoProgression::has(2, 3, 3));
    }
}
