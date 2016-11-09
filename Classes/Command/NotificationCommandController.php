<?php
namespace NIMIUS\Workshops\Command;

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
 * Notification command controller.
 *
 * Responsible for delivering various notification e-mails to attendee.
 */
class NotificationCommandController extends AbstractCommandController
{

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\WorkshopRepository
     * @inject
     */
    protected $workshopRepository;

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\DateRepository
     * @inject
     */
    protected $dateRepository;

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\RegistrationRepository
     * @inject
     */
    protected $registrationRepository;

    /**
     * @var \NIMIUS\Workshops\Mailer\RegistrationMailer
     * @inject
     */
    protected $registrationMailer;

    /**
     * Deliver registration confirmation mails.
     *
     * This delivers a registration confirmation to the attendee,
     * and optionally a notification to the workshop owner.
     *
     * @return bool true if task run was successful
     */
    public function registrationConfirmationCommand()
    {
        $registrations = $this->registrationRepository->findAllWithoutSentConfirmation();
        foreach ($registrations as $registration) {
            $this->registrationMailer->deliverRegistrationConfirmation($registration);
        }
        return true;
    }
}
