<?php
namespace NIMIUS\Workshops\Controller;

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
use NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy;

/**
 * Controller for displaying workshop dates.
 */
class DatesController extends AbstractController
{
    /**
     * Index action.
     *
     * Displays all upcoming dates over all workshops.
     *
     * @param 
     * @return void
     */
    public function indexAction(Location $location = NULL)
    {
        $proxy = $this->objectManager->get(DateRepositoryProxy::class);
        $proxy->initializeFromSettings($this->settings);
        if ($location) {
            $proxy->setLocation($location);
        }
        $this->view->assignMultiple([
            'upcomingDates' => $this->dateRepository->findByProxy($proxy)
        ]);
    }
}