<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use DateTime;
use PHPUnit\Framework\TestCase;
use Przeslijmi\Sivalidator\Date;
use Przeslijmi\Sivalidator\Exceptions\DateFormatReturnUnknownException;
use Przeslijmi\Sivalidator\Exceptions\DateFormatWrongException;
use Przeslijmi\Sivalidator\Exceptions\DateFormatWrongObjectException;
use Przeslijmi\Sivalidator\Exceptions\DateMoveWrongSyntaxException;
use stdClass;

/**
 * Methods for testing Date.
 */
final class DateTest extends TestCase
{

    /**
     * Provides various date formats to test.
     *
     * @return array[][]
     */
    public function datesProvider() : array
    {

        // Lvd.
        $dtObj = new DateTime('2019-01-01');

        return [
            [ $dtObj,       '2019-01-01', 'DateTimeObject' ],
            [ '2019',       '2019-01-01', 'YearPeriod'     ],
            [ '2019H1',     '2019-01-01', 'HalfYearPeriod' ],
            [ '2019H2',     '2019-07-01', 'HalfYearPeriod' ],
            [ '2019Q1',     '2019-01-01', 'QuarterPeriod'  ],
            [ '2019Q2',     '2019-04-01', 'QuarterPeriod'  ],
            [ '2019M01',    '2019-01-01', 'MonthPeriod'    ],
            [ '2019M02',    '2019-02-01', 'MonthPeriod'    ],
            [ '2019W01',    '2019-01-01', 'WeekPeriod'     ],
            [ '2019W02',    '2019-01-08', 'WeekPeriod'     ],
            [ '2019D001',   '2019-01-01', 'DatePeriod'     ],
            [ '2019D002',   '2019-01-02', 'DatePeriod'     ],
            [ '2019-01-01', '2019-01-01', 'YmdDate'        ],
        ];
    }

    /**
     * Provides various date formats to test.
     *
     * @return array[][]
     */
    public function movesProvider() : array
    {

        return [
            [ '1Y', '2020-01-01' ],
            [ '1H', '2019-07-01' ],
            [ '1Q', '2019-04-01' ],
            [ '1M', '2019-02-01' ],
            [ '1W', '2019-01-08' ],
            [ '1D', '2019-01-02' ],
            [ '2Y', '2021-01-01' ],
            [ '2H', '2020-01-01' ],
            [ '2Q', '2019-07-01' ],
            [ '2M', '2019-03-01' ],
            [ '2W', '2019-01-15' ],
            [ '2D', '2019-01-03' ],
            [ '-1Y', '2018-01-01' ],
            [ '-1H', '2018-07-01' ],
            [ '-1Q', '2018-10-01' ],
            [ '-1M', '2018-12-01' ],
            [ '-1W', '2018-12-25' ],
            [ '-1D', '2018-12-31' ],
            [ '-2Y', '2017-01-01' ],
            [ '-2H', '2018-01-01' ],
            [ '-2Q', '2018-07-01' ],
            [ '-2M', '2018-11-01' ],
            [ '-2W', '2018-12-18' ],
            [ '-2D', '2018-12-30' ],
        ];
    }

    /**
     * Provides various date formats to test.
     *
     * @return array[][]
     */
    public function returnsProvider() : array
    {

        // Lvd.
        $dtObj = new DateTime('2020-01-01');

        return [
            [ '2020-01-01', null,             '2020-01-01' ],
            [ '2020-01-01', 'YmdDate',        '2020-01-01' ],
            [ '2020-01-01', 'YearPeriod',     '2020'       ],
            [ '2020-01-01', 'HalfYearPeriod', '2020H1'     ],
            [ '2020-01-01', 'QuarterPeriod',  '2020Q1'     ],
            [ '2020-01-01', 'MonthPeriod',    '2020M01'    ],
            [ '2020-01-01', 'WeekPeriod',     '2020W01'    ],
            [ '2020-01-01', 'DayPeriod',      '2020D001'   ],
            [ '2020-01-01', 'DateTimeObject', $dtObj       ],
        ];
    }

    /**
     * Provides various wrong periods as strings.
     *
     * @return array[][]
     */
    public function wrongStringProvider() : array
    {

        return [
            [ '1'        ],
            [ '2019H0'   ],
            [ '2019H3'   ],
            [ '2019Q0'   ],
            [ '2019Q5'   ],
            [ '2019Q03'  ],
            [ '2019M00'  ],
            [ '2019M13'  ],
            [ '2019D467' ],
            [ '2019D0'   ],
            [ '2019D00'  ],
            [ '2019D1'   ],
        ];
    }

    /**
     * Test if creation works.
     *
     * @param DataTime|string $period    Period to test.
     * @param string          $startDate Calculated proper start date.
     * @param string          $format    Calculated proper format of `$period`.
     *
     * @return void
     *
     * @dataProvider datesProvider
     */
    public function testCreation($period, string $startDate, string $format) : void
    {

        $date = new Date($period);

        $this->assertEquals($startDate, $date->get());
        $this->assertEquals($format, $date->getFormat());
    }

    /**
     * Test if moving works.
     *
     * @param DataTime|string $period    Period to test.
     * @param string          $startDate Calculated proper start date.
     * @param string          $format    Calculated proper format of `$period`.
     *
     * @return void
     *
     * @dataProvider movesProvider
     */
    public function testMoving(string $move, string $expectedDate) : void
    {

        $date = new Date('2019');
        $date->move($move);

        $this->assertEquals($expectedDate, $date->get());
    }

    /**
     * Test if moving works.
     *
     * @param string          $dateToTest     Date (Y-m-d) to be tested.
     * @param null|string     $returnFormat   What format to call.
     * @param DataTime|string $expectedReturn Expected result of transferring date to format.
     *
     * @return void
     *
     * @dataProvider returnsProvider
     */
    public function testReturning(string $dateToTest, $returnFormat, $expectedReturn) : void
    {

        $date = new Date($dateToTest);

        if (is_null($returnFormat)) {
            $this->assertEquals($expectedReturn, $date->get());
        } else {
            $this->assertEquals($expectedReturn, $date->get($returnFormat));
        }
    }

    /**
     * Test if sending wrong object to class throws.
     *
     * @return void
     */
    public function testIfSendingWrongObjectThrows() : void
    {

        $this->expectException(DateFormatWrongObjectException::class);

        new Date(new stdClass());
    }

    /**
     * Test if sending wrong datatype to class throws.
     *
     * @return void
     */
    public function testIfSendingWrongDataTypeThrows() : void
    {

        $this->expectException(DateFormatWrongObjectException::class);

        new Date(true);
    }

    /**
     * Test if sending string wrong syntaxed period throws.
     *
     * @param string $wrongPeriod Wrong period to be tested.
     *
     * @return void
     *
     * @dataProvider wrongStringProvider
     */
    public function testIfSendingWrongSyntaxedPeriodThrows(string $wrongPeriod) : void
    {

        $this->expectException(DateFormatWrongException::class);

        new Date($wrongPeriod);
    }

    /**
     * Test if sending string wrong syntaxed period throws.
     *
     * @return void
     */
    public function testIfMovingWithWrongUnitThrows() : void
    {

        $this->expectException(DateMoveWrongSyntaxException::class);

        $date = new Date('2019');
        $date->move('1T');
    }

    /**
     * Test if asking for wrong return format throws.
     *
     * @return void
     */
    public function testIfAskingForWrongReturnFormatThrows() : void
    {

        $this->expectException(DateFormatReturnUnknownException::class);

        $date = new Date('2019');
        $date->get('NonexistingPeriod');
    }
}
