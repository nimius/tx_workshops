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
 * View helper for formatting strings iCalendar-compliant.
 *
 * @see https://tools.ietf.org/html/rfc6868
 */
class StringViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Escapes given input to match specification.
     *
     * Commas and semi-colons must be escaped, where a circumflex accent ("^")
     * is used as the escape character.
     *
     * @return string
     */
    public function render()
    {
        return preg_replace('/[,;]/m', '^\0', $this->renderChildren());
    }
}
