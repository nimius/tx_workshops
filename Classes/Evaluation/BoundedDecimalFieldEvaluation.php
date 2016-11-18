<?php
namespace NIMIUS\Workshops\Evaluation;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Bounded decimal:
 * Is formatted to have a fixed number of decimals as well as a minimum and maximum
 * value.
 */
abstract class BoundedDecimalFieldEvaluation implements EvaluationInterface
{
    /**
     * Bounds of the Evaluated number where the first item is the
     * lower bound and the second item the upper bound
     * 
     * @var int[]
     */
    protected $bounds = [0, 100];

    /**
     * Number of decimal places to format to
     *
     * @var int
     */
    protected $decimals = 2;

    /**
     * JavaScript code for client side validation/evaluation
     *
     * @return string JavaScript code for client side validation/evaluation
     */
    public function returnFieldJS() {
        return '
            return Math.min(' . $this->bounds[1] . ', Math.max(' . $this->bounds[0] . ', parseFloat(value))).toFixed(' . $this->decimals . ')
        ';
    }

    /**
     * Server-side validation/evaluation on saving the record
     *
     * @param string $value The field value to be evaluated
     * @param string $is_in The "is_in" value of the field configuration from TCA
     * @param bool $set Boolean defining if the value is written to the database or not. Must be passed by reference and changed if needed.
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value, $is_in, &$set) {
        return $this->evaluate($value);
    }

    /**
     * Server-side validation/evaluation on opening the record
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters) {
        return $this->evaluate($parameters['value']);
    }

    /**
     * Executes the server side evaluation
     *
     * @param mixed $value
     * @return string
     */
    protected function evaluate($value) {
        return number_format(min($this->bounds[1], max($this->bounds[0], (float)$value)), $this->decimals);
    }
}