<?php
namespace NIMIUS\Workshops\ViewHelpers\Widget;

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

/**
 * Locations widget view helper.
 *
 * Displays a list of locations to filter workshop dates.
 */
class LocationsViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper
{
    /**
     * @var \NIMIUS\Workshops\ViewHelpers\Widget\Controller\LocationsController
     * @inject
     */
    protected $controller;


    /**
     * Main method of this view helper.
     *
     * @param string $pluginName The plugin name to work with
     * @param string $controllerName The controller name
     * @return string
     */
    public function render($pluginName = 'Dates', $controllerName = NULL)
    {
        return $this->initiateSubRequest();
    }
}