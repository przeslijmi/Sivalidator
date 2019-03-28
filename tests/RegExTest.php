<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\RegexTestFailException;
use Przeslijmi\Sivalidator\RegEx;

/**
 * Methods for testing values against regex syntax.
 */
final class RegExTest extends TestCase
{

    /**
     * Test proper string and pattern and assert true.
     *
     * @return void
     */
    public function testIfHelloMatchesTrue1() : void
    {

        $this->assertTrue(Regex::ifMatches('Hello', '/^([A-Za-z]+)$/', false));
    }

    /**
     * Test proper string and pattern and assert true but leave third optional parameter.
     *
     * @return void
     */
    public function testIfHelloMatchesTrue2() : void
    {

        $this->assertTrue(Regex::ifMatches('Hello', '/^([A-Za-z]+)$/'));
    }

    /**
     * Test inproper string and pattern and assert false.
     *
     * @return void
     */
    public function testIfHelloMatchesFalse1() : void
    {

        $this->assertFalse(
            Regex::ifMatches('Hello 123', '/^([A-Za-z]+)$/', false),
            'Hello 123 should not match because it has digits.'
        );
    }

    /**
     * Test inproper string and pattern and assert exception.
     *
     * @return void
     */
    public function testIfHelloThrows() : void
    {

        $this->expectException(RegexTestFailException::class);

        Regex::ifMatches('Hello 123', '/^([A-Za-z]+)$/');
    }


    public function testIfWrotypeThrows1() : void
    {

        $this->expectException(\TypeError::class);

        Regex::ifMatches(new \stdClass(), '/^([A-Za-z]+)$/');
    }

    public function testIfWrotypeThrows2() : void
    {

        $this->expectException(\TypeError::class);

        Regex::ifMatches('aaa', true);
    }
}
