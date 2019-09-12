<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Date format is not a DateTime, nor a string.
 */
class DateFormatWrongObjectException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $classOrType Name of class of variable type that was sent.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $classOrType)
    {

        $this->addInfo('context', 'creatingDataObject');
        $this->addInfo('sentClassOrType', $classOrType);
        $this->addInfo('hint', 'Possible are DateTime or string.');
    }
}
