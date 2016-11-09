<?php
namespace NIMIUS\Workshops\Controller\Backend;

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

use NIMIUS\Workshops\Domain\Model\Date;

/**
 * Backend controller to manage registrations.
 */
class RegistrationsController extends AbstractController
{

    /**
     * Index action
     *
     * Lists all registrations for a given workshop date.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Date $date
     * @return void
     */
    public function indexAction(Date $date)
    {
        $this->assignDefaults();
        $this->view->assignMultiple([
            'workshop' => $date->getWorkshop(),
            'date' => $date,
            'registrations' => $date->getRegistrations()
        ]);
    }

    /**
     * Show action.
     *
     * Displays a registration.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Date $registration
     * @ignorevalidation $registration
     * @return void
     */
    public function showAction(\NIMIUS\Workshops\Domain\Model\Registration $registration)
    {
        $this->assignDefaults();
        $this->view->assignMultiple([
            'workshop' => $registration->getWorkshopDate()->getWorkshop(),
            'date' => $registration->getWorkshopDate(),
            'registration' => $registration
        ]);
    }
}
