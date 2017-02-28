<?php
namespace NIMIUS\Workshops\ViewHelpers\Format\ICalendar;

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
 * View helper for formatting timestamps iCalendar-compliant.
 *
 * @see https://tools.ietf.org/html/rfc6868
 */
class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Brings a given timestamp into the specified format.
     *
     * @return string
     */
    public function render()
    {
        return strftime('%Y%m%dT%H%M%S', (int)$this->renderChildren());
    }
}
