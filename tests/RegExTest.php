<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\RegexTestFailException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
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
        $this->assertFalse(Regex::ifNotMatches('Hello', '/^([A-Za-z]+)$/', false));
    }

    /**
     * Test proper string and pattern and assert true but leave third optional parameter.
     *
     * @return void
     */
    public function testIfHelloMatchesTrue2() : void
    {

        $this->assertTrue(Regex::ifMatches('Hello', '/^([A-Za-z]+)$/'));
        $this->assertFalse(Regex::ifNotMatches('Hello', '/^([A-Za-z]+)$/', false));
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
        $this->assertTrue(Regex::ifNotMatches('Hello 123', '/^([A-Za-z]+)$/', false));
    }

    /**
     * Test inproper string and pattern will throw exception.
     *
     * @return void
     */
    public function testIfHelloThrows1() : void
    {

        $this->expectException(RegexTestFailException::class);

        Regex::ifMatches('Hello 123', '/^([A-Za-z]+)$/');
    }

    /**
     * Test proper string and pattern will throw exception.
     *
     * @return void
     */
    public function testIfHelloThrows2() : void
    {

        $this->expectException(RegexTestFailException::class);

        Regex::ifNotMatches('Hello 123', '/^([A-Za-z0-9 ]+)$/');
    }

    /**
     * Test inproper type of first param of ifMatches throws.
     *
     * @return void
     */
    public function testIfWrotypeThrows1() : void
    {

        $this->expectException(\TypeError::class);

        Regex::ifMatches(new \stdClass(), '/^([A-Za-z]+)$/');
    }

    /**
     * Test inproper type of second param of ifMatches throws.
     *
     * @return void
     */
    public function testIfWrotypeThrows2() : void
    {

        $this->expectException(\TypeError::class);

        Regex::ifMatches('aaa', true);
    }

    /**
     * Test nonregex string in second param of ifMatches throws.
     *
     * @return void
     */
    public function testIfWrotypeThrows3() : void
    {

        $this->expectException(ParamWrosynException::class);

        Regex::ifMatches('aaa', 'WRONG_REGEX_STRING');
    }

    /**
     * Test nonregex string in second param of ifMatches throws.
     *
     * @return void
     */
    public function testIfWrotypeThrows4() : void
    {

        $this->expectException(ParamWrosynException::class);

        Regex::ifMatches('aaa', '/');
    }
}
