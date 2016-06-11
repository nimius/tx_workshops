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

use \NIMIUS\Workshops\Domain\Model\Date;

/**
 * Date record classes view helper.
 */
class DateRecordClassesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * This helper renders utilized classes per each date record
     * to keep the view clean and simple.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Date $date
     * @return array
     */
    public function render(Date $date)
    {
        $classes = [
            'minimum' => 'attendance-block-notice',
            'current' => 'attendance-block-notice',
            'maximum' => 'attendance-block-notice'
        ];
        
        if ($date->getRegistrations()->count() == 0) {
            $classes['current'] = 'attendance-block-warning';
        }
        
        if ($date->getMinimumAttendanceEnabled()) {
            if ($date->getAttendeesNeededForRequiredMinimum() == 0) {
                $classes['current'] = 'attendance-block-success';
            } else {
                $classes['minimum'] = $classes['current'] = 'attendance-block-warning';
            }
        }
        
        if ($date->getMaximumAttendanceEnabled()) {
            if ($date->getAttendeesNeededForPossibleMaximum() == 0) {
                $classes['maximum'] ='attendance-block-warning';
            }
        }
        
        return $classes;
    }

}