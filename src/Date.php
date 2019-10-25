<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use DateInterval;
use DateTime;
use Przeslijmi\Sivalidator\Exceptions\DateFormatReturnUnknownException;
use Przeslijmi\Sivalidator\Exceptions\DateFormatWrongException;
use Przeslijmi\Sivalidator\Exceptions\DateFormatWrongObjectException;
use Przeslijmi\Sivalidator\Exceptions\DateMoveWrongSyntaxException;
use Przeslijmi\Sivalidator\RegEx;

/**
 * Date counting solution.
 *
 * ## Usage example
 * ```
 * $date = new \Przeslijmi\Sivalidator\Date('2019H2');
 * $date->move('1Y2W-2D');
 * var_dump($date->get()); // will return 2022-07-13
 * ```
 */
class Date
{

    /**
     * Date as string.
     *
     * @var string
     */
    private $date;

    /**
     * What was the original format of given date.
     *
     * Possible options:
     *   - DateTimeObject
     *   - YearPeriod
     *   - HalfYearPeriod
     *   - QuarterPeriod
     *   - MonthPeriod
     *   - WeekPeriod
     *   - DayPeriod
     *   - YmdDate
     *
     * @var string
     */
    private $format;

    /**
     * Constructor.
     *
     * @param DateTime|string $date Date as DateTime object or string.
     *
     * @since v1.0
     */
    public function __construct($date)
    {

        $this->setDate($date);
    }

    /**
     * Setter (and validator) for date.
     *
     * @param DateTime|string $date Date as DateTime object or string.
     *
     * @return self
     * @throws DateFormatWrongObjectException When format is not DateTime, nor string.
     * @throws DateFormatWrongException       When format is string but unrecognizable.
     * @since  v1.0
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    private function setDate($date) : self
    {

        // This is a DateTime object.
        if (is_a($date, 'DateTime') === true) {

            // Save.
            $this->date   = $date->format('Y-m-d');
            $this->format = 'DateTimeObject';

            return $this;
        }

        // This is not a string, nor a DateTime - throw.
        if (is_object($date) === true) {
            throw new DateFormatWrongObjectException(get_class($date));
        } elseif (is_string($date) === false) {
            throw new DateFormatWrongObjectException(gettype($date));
        }

        // Lvd.
        $monthRegex = '/^([0-9]){4}(M)((01)|(02)|(03)|(04)|(05)|(06)|(07)|(08)|(09)|(10)|(11)|(12))$/';

        // Find proper date if string.
        if (RegEx::ifMatches($date, '/^([0-9]){4}$/', false) === true) {

            // Define.
            $date   = ( new DateTime($date . '-01-01') );
            $format = 'YearPeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}(H)([12])$/', false) === true) {

            // Lvd.
            list($year, $halfyear) = explode('H', $date);

            // Define.
            $date   = ( new DateTime($year . '-' . ( ( ( (int) $halfyear - 1 ) * 6 ) + 1 ) . '-01') );
            $format = 'HalfYearPeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}(Q)([1234])$/', false) === true) {

            // Lvd.
            list($year, $quarter) = explode('Q', $date);

            // Define.
            $date   = ( new DateTime($year . '-' . ( ( ( (int) $quarter - 1 ) * 3 ) + 1 ) . '-01') );
            $format = 'QuarterPeriod';

        } elseif (RegEx::ifMatches($date, $monthRegex, false) === true) {

            // Lvd.
            list($year, $month) = explode('M', $date);

            // Define.
            $date   = ( new DateTime($year . '-' . $month . '-01') );
            $format = 'MonthPeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}(W)([012345])([0-9])$/', false) === true) {

            // Lvd.
            list($year, $week) = explode('W', $date);
            $days              = ( ( ( (int) $week ) - 1 ) * 7 );

            // Define.
            $date   = ( new DateTime($year . '-01-01') )->add(new DateInterval('P' . $days . 'D'));
            $format = 'WeekPeriod';

        } elseif (RegEx::ifMatches($date, '/^(20)([12])([0-9])(D)([0123])([0-9])([0-9])$/', false) === true) {

            // Lvd.
            list($year, $day) = explode('D', $date);
            $days             = ( ( (int) $day ) - 1 );

            // Define.
            $date   = ( new DateTime($year . '-01-01') )->add(new DateInterval('P' . $days . 'D'));
            $format = 'DatePeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}-([0-9]){2}-([0-9]){2}$/', false) === true) {

            // Lvd.
            list($year, $month, $day) = explode('-', $date);

            // Define.
            $date   = new DateTime($year . '-' . $month . '-' . $day);
            $format = 'YmdDate';

        } else {

            // Nothing fits - throw.
            throw new DateFormatWrongException($date);
        }//end if

        // Save date & format.
        $this->date   = $date->format('Y-m-d');
        $this->format = $format;

        return $this;
    }

    /**
     * Move date with given move description.
     *
     * ## Usage example
     * ```
     * $date = ( new Date('2019') )->move('1Y');
     * ```
     *
     * ## Possible units
     * - `Y` - for year
     * - `H` - for halfyear
     * - `Q` - for quarter
     * - `M` - for month
     * - `W` - for week
     * - `D` - for day
     *
     * @param string $move Move description.
     *
     * @throws DateMoveWrongSyntaxException When move order has wrong syntax.
     * @return self
     * @since  v1.0
     */
    public function move(string $move) : self
    {

        // Lvd.
        $moveMonths = 0;
        $moveDays   = 0;

        // Find moves.
        preg_match_all('/(-)?([0-9])+([YHQMWD]){1}/', $move, $foundMoves);
        $foundMoves = $foundMoves[0];

        // Throw.
        if (count($foundMoves) === 0 && empty($move) === false) {
            throw new DateMoveWrongSyntaxException($move);
        }

        // Serve every move.
        foreach ($foundMoves as $move) {

            // Lvd.
            // Amount can be negative.
            $amount = (int) substr($move, 0, -1);
            $unit   = (string) substr($move, -1);

            // Get starting date.
            list($year, $month, $day) = explode('-', $this->date);

            // Integerize.
            $month = (int) $month;
            $day   = (int) $day;
            $year  = (int) $year;

            // Recalc move.
            switch ($unit) {
                case 'Y':
                    $moveMonths = ( 12 * $amount );
                break;

                case 'H':
                    $moveMonths = ( 6 * $amount );
                break;

                case 'Q':
                    $moveMonths = ( 3 * $amount );
                break;

                case 'M':
                    $moveMonths = $amount;
                break;

                case 'W':
                    $moveDays = ( 7 * $amount );
                break;

                case 'D':
                default:
                    $moveDays = $amount;
                break;
            }//end switch

            // Make move.
            $this->date = date('Y-m-d', mktime(0, 0, 0, ( $month + $moveMonths ), ( $day + $moveDays ), $year));

        }//end foreach

        return $this;
    }

    /**
     * Get date as Y-m-d string.
     *
     * @param string $format Format name to use on return, ie. DateTimeObject, YmdDate,
     *                       YearPeriod, HalfYearPeriod, QuarterPeriod, MonthPeriod, WeekPeriod,
     *                       DayPeriod.
     *
     * @throws DateFormatReturnUnknownException When unknown format has been sent.
     * @return DateTime|string
     * @since  v1.0
     */
    public function get(string $format = 'YmdDate')
    {

        // Lvd.
        list($year, $month, $day) = explode('-', $this->date);

        // Integerize.
        $month = (int) $month;
        $day   = (int) $day;
        $year  = (int) $year;

        // Deliver depending on format.
        switch ($format) {

            case 'DateTimeObject':
            return new DateTime($this->date);

            case 'YmdDate':
            return $this->date;

            case 'YearPeriod':
            return (string) $year;

            case 'HalfYearPeriod':
            return (string) $year . 'H' . ceil(( $month / 6 ));

            case 'QuarterPeriod':
            return (string) $year . 'Q' . ceil(( $month / 3 ));

            case 'MonthPeriod':
            return (string) $year . 'M' . str_pad((string) $month, 2, '0', STR_PAD_LEFT);

            case 'WeekPeriod':
                $weekNo = (int) ( new DateTime($this->date) )->format('W');
            return (string) $year . 'W' . str_pad((string) $weekNo, 2, '0', STR_PAD_LEFT);

            case 'DayPeriod':
                $weekNo = (int) ( ( new DateTime($this->date) )->format('z') + 1 );
            return (string) $year . 'D' . str_pad((string) $weekNo, 3, '0', STR_PAD_LEFT);

            default:
            throw new DateFormatReturnUnknownException($format);
        }//end switch
    }

    /**
     * Getter for format.
     *
     * @since  v1.0
     * @return string
     */
    public function getFormat() : string
    {

        return $this->format;
    }
}
