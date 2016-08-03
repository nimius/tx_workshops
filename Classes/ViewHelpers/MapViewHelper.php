<?php
namespace NIMIUS\Workshops\ViewHelpers;

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

use NIMIUS\Workshops\Domain\Model\Location;
use NIMIUS\Workshops\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Map view helper.
 *
 * Embeds google maps for the given location.
 *
 * @todo extend to be able to use various features.
 */
class MapViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'iframe';


    /**
     * Arguments initialization.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('width', 'integer', 'Iframe width', false, 300);
        $this->registerTagAttribute('height', 'integer', 'Iframe height', false, 200);
        $this->registerTagAttribute('zoom', 'integer', 'Zoom level', false, 16);
    }

    /**
     * Renders a map.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Location $location
     * @return string
     */
    public function render(Location $location)
    {   
        $query = 'http://maps.google.com/maps?q='
            . $location->getLatitude() . ',' . $location->getLongitude()
            . '&z=' . $this->arguments['zoom']
            . '&output=embed';
        
        $this->tag->forceClosingTag(true);
        $this->tag->addAttribute('src', $query);
        return $this->tag->render();
    }
}