<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Date format was moved by unknown formula.
 */
class DateMoveWrongSyntaxException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $move Formula of move.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $move)
    {

        $this->addInfo('context', 'movingDataObject');
        $this->addInfo('moveUsed', $move);
        $this->addInfo('hint', 'Possible moves are Y, H, Q, M, W, D; positive (without +) or negative (with -).');
    }
}
