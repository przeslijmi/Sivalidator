<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Date format is corrupted.
 */
class DateFormatWrongException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $date Date that was unrecognized.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $date)
    {

        $this->addInfo('context', 'creatingDataObject');
        $this->addInfo('sentDate', $date);
        $this->addInfo('hint', 'Possible formats are 2019, 2019H1, 2019Q1, 2019M01, 2019W01, 2019D001, 2019-01-01. Other format was sent or format has syntax error.');
    }
}
