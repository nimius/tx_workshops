<?php
namespace NIMIUS\Workshops\Report\Status;

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

use NIMIUS\Workshops\Mailer\RegistrationMailer;
use NIMIUS\Workshops\Utility\ConfigurationUtility;
use NIMIUS\Workshops\Utility\ObjectUtility;
use TYPO3\CMS\Reports\Status;

/**
 * Checks if basically required configurations are present.
 */
class ConfigurationStatus implements \TYPO3\CMS\Reports\StatusProviderInterface
{

    /**
     * Main method.
     *
     * Executes status checks against configurations.
     *
     * @return array
     */
    public function getStatus()
    {
        return [
            'RegistrationConfirmationMailerStatus' => $this->getRegistrationConfirmationMailerStatus()
        ];
    }

    /**
     * Status check for registration mailer's confirmation email configuration.
     *
     * @todo Implement language labels
     * @return \TYPO3\CMS\Reports\Status
     */
    protected function getRegistrationConfirmationMailerStatus()
    {
        $registrationMailer = ObjectUtility::getObjectManager()->get(RegistrationMailer::class);
        $configuration = ConfigurationUtility::getTyposcriptConfiguration()['registration.']['confirmationEmail.'];
        $registrationMailer->prepareMailConfiguration($configuration);
        if ($registrationMailer->mailConfigurationAllowsSendingEmails($configuration)) {
            return ObjectUtility::getObjectManager()->get(
                Status::class,
                'Registration: Confirmation mailer',
                'OK',
                null,
                Status::OK
            );
        } else {
            return ObjectUtility::getObjectManager()->get(
                Status::class,
                'Registration: Confirmation mailer',
                'Not functional',
                'The configuration available to deliver confirmation mails to registrants is incomplete and will most likely lead to mails not being delivered at all!',
                Status::ERROR
            );
        }
    }
}
