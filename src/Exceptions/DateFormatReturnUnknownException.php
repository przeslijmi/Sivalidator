<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Date format for return is corrupted.
 */
class DateFormatReturnUnknownException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $returnFormat Return format sent to use.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $returnFormat)
    {

        $this->addInfo('context', 'returningDataObject');
        $this->addInfo('returnFormat', $returnFormat);
        $this->addInfo('hint', 'Possible return formats are DateTimeObject, YmdDate, YearPeriod, HalfYearPeriod, QuarterPeriod, MonthPeriod, WeekPeriod, DayPeriod. Other return format was sent.');
    }
}
