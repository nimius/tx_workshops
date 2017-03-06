<?php
namespace NIMIUS\Workshops\Mailer;

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

use NIMIUS\Workshops\Domain\Model\Registration;
use NIMIUS\Workshops\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Registration mailer.
 *
 * Responsible for mails regarding registrations.
 */
class RegistrationMailer extends AbstractMailer
{
    /**
     * @var \NIMIUS\Workshops\Domain\Repository\RegistrationRepository
     * @inject
     */
    protected $registrationRepository;

    /**
     * Delivers registration confirmation mails.
     *
     * @param Registration $registration
     * @return bool
     */
    public function deliverRegistrationConfirmation(Registration $registration)
    {
        $settings = ConfigurationUtility::getTyposcriptConfiguration()['registration.']['confirmationEmail.'];
        $this->prepareMailConfiguration($settings);
        if (!$this->mailConfigurationAllowsSendingEmails($settings)) {
            return false;
        }

        /*
         * TODO FIXME: Currently, all three get the mail in the same language as defined in the
         * given registration's language, as switching language is not easily possible.
         * At least the backoffice address should get emails in their preferred language.
         */
        if ($registration->getLanguage()) {
            $this->setLanguage($registration->getLanguage()->getLanguageIsoCode());
        }

        if ((int)$settings['attendee']) {
            try {
                $body = $this->renderEmailTemplate(
                    'Registration/Confirmation/Attendee.html',
                    [
                        'registration' => $registration,
                        'date' => $registration->getWorkshopDate(),
                        'workshop' => $registration->getWorkshopDate()->getWorkshop()
                    ]
                );
                $mail = $this->createMailMessage();
                if (!empty($settings['mailFromAddress'])) {
                    $mail->setFrom([$settings['mailFromAddress'] => $settings['mailFromName']]);
                }
                $mail->setTo([$registration->getEmail() => $registration->getFullName()]);
                $mail->setSubject($this->getLanguageLabel('mailer.registration.deliverRegistrationConfirmation.attendee.subject'));
                $mail->setBody($body, 'text/html');

                if ($mail->send()) {
                    $registration->setConfirmationSentAt(time());
                    $this->registrationRepository->update($registration);
                }
            } catch (\Swift_TransportException $e) {
                // Mail could not be sent.
            }
        }

        if ((int)$settings['instructor']) {
            try {
                $instructor = $registration->getWorkshopDate()->getInstructor();
                if ($instructor) {
                    $body = $this->renderEmailTemplate(
                        'Registration/Confirmation/Instructor.html',
                        [
                            'registration' => $registration,
                            'date' => $registration->getWorkshopDate(),
                            'workshop' => $registration->getWorkshopDate()->getWorkshop()
                        ]
                    );
                    $mail = $this->createMailMessage();
                    if (!empty($settings['mailFromAddress'])) {
                        $mail->setFrom([$settings['mailFromAddress'] => $settings['mailFromName']]);
                    }
                    $mail->setTo([$instructor->getEmail() => $instructor->getName()]);
                    $mail->setSubject($this->getLanguageLabel('mailer.registration.deliverRegistrationConfirmation.instructor.subject'));
                    $mail->setBody($body, 'text/html');
                    $mail->send();
                }
            } catch (\Swift_TransportException $e) {
                // Mail could not be sent.
            }
        }

        if ((int)$settings['backOffice']) {
            try {
                $body = $this->renderEmailTemplate(
                    'Registration/Confirmation/BackOffice.html',
                    [
                        'registration' => $registration,
                        'date' => $registration->getWorkshopDate(),
                        'workshop' => $registration->getWorkshopDate()->getWorkshop()
                    ]
                );
                $mail = $this->createMailMessage();
                if (!empty($settings['mailFromAddress'])) {
                    $mail->setFrom([$settings['mailFromAddress'] => $settings['mailFromName']]);
                }
                $mail->setSubject($this->getLanguageLabel('mailer.registration.deliverRegistrationConfirmation.backOffice.subject'));
                $mail->setBody($body, 'text/html');

                $recipients = GeneralUtility::trimExplode(',', $settings['backOffice.']['recipients']);
                foreach ($recipients as $emailAddress) {
                    $mail->setTo($emailAddress);
                    $mail->send();
                }
            } catch (\Swift_TransportException $e) {
                // Mail could not be sent.
            }
        }
    }

    /**
     * Helper method to check if the given configuration allows
     * sending emails.
     *
     * @api
     * @param array $configuration
     * @return bool
     */
    public function mailConfigurationAllowsSendingEmails($configuration)
    {
        return !empty($configuration['mailFromAddress']);
    }

    /**
     * Prepare mail configuration by setting defaults from either
     * install tool or typoscript.
     *
     * @param array &$config Configuration to eventually adapt
     * @return void
     */
    public function prepareMailConfiguration(&$config)
    {
        // Configuration from install tool
        $mailConfig = ConfigurationUtility::getMailConfiguration();

        // Default configuration from typscript
        $defaultConfig = ConfigurationUtility::getTyposcriptConfiguration()['registration.'];

        if (empty($config['mailFromName'])) {
            if (!empty($defaultConfig['mailFromName'])) {
                $config['mailFromName'] = $defaultConfig['mailFromName'];
            } else {
                $config['mailFromName'] = $mailConfig['defaultMailFromName'];
            }
        }
        if (empty($config['mailFromAddress'])) {
            if (!empty($defaultConfig['mailFromAddress'])) {
                $config['mailFromAddress'] = $defaultConfig['mailFromAddress'];
            } else {
                $config['mailFromAddress'] = $mailConfig['defaultMailFromAddress'];
            }
        }
    }
}
