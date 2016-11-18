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
 * Formats longitude values for storage in a DECIMAL(11,8) field
 */
class LongitudeEvaluation extends BoundedDecimalFieldEvaluation
{
    /**
     * @var int[]
     */
    protected $bounds = [-180, 180];

    /**
     * @var int
     */
    protected $decimals = 8;

}