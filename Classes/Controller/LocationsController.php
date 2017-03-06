<?php
namespace NIMIUS\Workshops\Controller;

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

use NIMIUS\Workshops\Domain\Model\Location;

/**
 * Controller for displaying locations.
 */
class LocationsController extends AbstractController
{
    /**
     * Show action
     *
     * Displays the location
     *
     * @param \NIMIUS\Workshops\Domain\Model\Location $location
     * @dontvalidate $location
     * @return void
     */
    public function showAction(Location $location)
    {
        $this->view->assign('location', $location);
    }
}
