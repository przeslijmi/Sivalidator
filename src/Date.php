<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

use DateTime;
use Exception;
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
     * @since  v1.0
     * @return self
     */
    private function setDate($date) : self
    {

        // Find proper date.
        if (is_a($date, 'DateTime')) {
            $date = $date;
            $format = 'DateTimeObject';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}$/', false) === true) {
            $date = (new DateTime($date . '-01-01'));
            $format = 'YearPeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}(H)([12])$/', false) === true) {
            list($year, $halfyear) = explode('H', $date);
            $date = (new DateTime($year . '-' . ((((int) $halfyear - 1) * 6) + 1) . '-01'));
            $format = 'HalfYearPeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}(Q)([1234])$/', false) === true) {
            list($year, $quarter) = explode('Q', $date);
            $date = (new DateTime($year . '-' . ((((int)$quarter - 1) * 3) + 1) . '-01'));
            $format = 'QuarterPeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}(M)([01])([0-9])$/', false) === true) {
            list($year, $month) = explode('M', $date);
            $date = (new DateTime($year . '-' . $month . '-01'));
            $format = 'MonthPeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}(W)([012345])([0-9])$/', false) === true) {
            list($year, $week) = explode('W', $date);
            $days = ( ( ( (int) $week ) - 1 ) * 7 );
            $date = (new DateTime($year . '-01-01'))->add(new DateInterval('P' . $days . 'D'));
            $format = 'WeekPeriod';

        } elseif (RegEx::ifMatches($date, '/^(20)([12])([0-9])(D)([0123])([0-9])([0-9])$/', false) === true) {
            list($year, $day) = explode('D', $date);
            $date = (new DateTime($year . '-01-01'))->add(new DateInterval('P' . ((int) $day ) . 'D'));
            $format = 'DatePeriod';

        } elseif (RegEx::ifMatches($date, '/^([0-9]){4}-([0-9]){2}-([0-9]){2}$/', false) === true) {
            list($year, $month, $day) = explode('-', $date);
            $date = new DateTime($year . '-' . $month . '-' . $day);
            $format = 'YmdDate';

        } else {
            throw new Exception('formatUnknown');
        }

        // Save date & format.
        $this->date   = $date->format('Y-m-d');
        $this->format = $format;

        return $this;
    }

    /**
     * Move date with given move description.
     *
     * @param string $move Move description.
     *
     * @return self
     */
    public function move(string $move) : self
    {

        // Lvd.
        $moveMonths = 0;
        $moveDays   = 0;

        // Find moves.
        preg_match_all('/(-)?([0-9])+([YHQMWD]){1}/', $move, $foundMoves);
        $foundMoves = $foundMoves[0];

        // Serve every move.
        foreach ($foundMoves as $move) {

            // Lvd.
            $amount = (int) substr($move, 0, -1); // can be negative
            $unit   = (string) substr($move, -1);

            // Get starting date.
            list($year, $month, $day) = explode('-', $this->date);
            $month = (int) $month;
            $day   = (int) $day;
            $year  = (int) $year;

            // Recalc move.
            switch ($unit)
            {
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
                    $moveDays = $amount;
                break;
            }

            // Make move.
            $this->date = date('Y-m-d', mktime(0, 0, 0, ( $month + $moveMonths ), ( $day + $moveDays ), $year));
        }

        return $this;
    }

    /**
     * Get date as Y-m-d string.
     *
     * @since  v1.0
     * @return DateTime|string
     */
    public function get(string $format = 'YmdDate')
    {

        // Lvd.
        list($year, $month, $day) = explode('-', $this->date);
        $month = (int) $month;
        $day   = (int) $day;
        $year  = (int) $year;

        // Deliver depending on format
        switch ($format)
        {

            case 'DateTimeObject':
                return new DateTime($this->date);
            break;

            case 'YmdDate':
                return $this->date;
            break;

            case 'YearPeriod':
                return (string) $year;
            break;

            case 'HalfYearPeriod':
                return (string) $year . 'H' . ceil( $month / 6 );
            break;

            case 'QuarterPeriod':
                return (string) $year . 'Q' . ceil( $month / 3 );
            break;

            case 'MonthPeriod':
                return (string) $year . 'M' . str_pad($month, 2, '0', STR_PAD_LEFT);
            break;

            case 'WeekPeriod':
                $weekNo = (int) (new DateTime($this->date))->format('W');
                return (string) $year . 'W' . str_pad($weekNo, 3, '0', STR_PAD_LEFT);
            break;

            case 'DayPeriod':
                $weekNo = (int) ( (new DateTime($this->date))->format('z') + 1 );
                return (string) $year . 'D' . str_pad($weekNo, 3, '0', STR_PAD_LEFT);
            break;
        }

        throw new Exception('unknownDateFormat');
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
