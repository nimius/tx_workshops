<?php
namespace NIMIUS\Workshops\ViewHelpers\Widget\Controller;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Locations controller for "locations" widget.
 */
class LocationsController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController
{
    /**
     * @var \NIMIUS\Workshops\Domain\Repository\LocationRepository
     * @inject
     */
    protected $locationRepository;


    /**
     * Default action for this widget controller.
     *
     * @return void
     */
    public function indexAction()
    {
        $pluginArguments = GeneralUtility::_GP('tx_workshops_dates');
        if ((int)$pluginArguments['location']) {
            $activeLocation = $this->locationRepository->findByUid((int)$pluginArguments['location']);
        }
        $locations = $this->locationRepository->findAll();
        $this->view->assignMultiple([
            'locations' => $locations,
            'activeLocation' => $activeLocation
        ]);
    }
}