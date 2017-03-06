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
 * Latitude evaluation.
 *
 * Formats Latitude values for storage in a DECIMAL(10,8) Field
 */
class LatitudeEvaluation extends BoundedDecimalFieldEvaluation
{
    /**
     * @var int[]
     */
    protected $bounds = [-90, 90];

    /**
     * @var int
     */
    protected $decimals = 8;
}
