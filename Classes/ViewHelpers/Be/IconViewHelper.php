<?php
namespace NIMIUS\Workshops\ViewHelpers\Be;

/**
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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Abstraction over the IconViewHelpers.
 *
 * With 7.5 The old f.be.buttons.icon viewhelper was deprecated in favour of the
 * new IconViewHelper. Because we want to be backwards compatible, we use the new
 * ViewHelper, when possible and fallback to the deprecated one, when not.
 */
class IconViewHelper extends AbstractViewHelper
{

    /**
     * @param {string} $identifier
     *
     * @return string
     */
    public function render($identifier)
    {
        // using string identifier and not importing the namespaces, because that would cause fatal
        // errors, if one of the 2 classes does not exist.
        if (class_exists('\\TYPO3\\CMS\\Core\\ViewHelpers\\IconViewHelper')) {
            return (new \TYPO3\CMS\Core\ViewHelpers\IconViewHelper())->render($identifier);
        } else {
            return (new \TYPO3\CMS\Fluid\ViewHelpers\Be\Buttons\IconViewHelper())->render('', $identifier);
        }
    }

}