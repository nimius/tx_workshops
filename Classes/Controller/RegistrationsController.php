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

use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Domain\Model\Date;
use NIMIUS\Workshops\Domain\Model\Registration;
use NIMIUS\Workshops\Persistence\Session;
use NIMIUS\Workshops\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller to handle registrations.
 */
class RegistrationsController extends AbstractController
{

    /**
     * @var \NIMIUS\Workshops\Persistence\Session
     * @inject
     */
    protected $session;

	/**
	 * @var \NIMIUS\Workshops\Mailer\RegistrationMailer
	 * @inject
	 */
	protected $registrationMailer;

	/**
	 * @var \NIMIUS\Workshops\Domain\Repository\LanguageRepository
	 * @inject
	 */
	protected $languageRepository;


	/**
	 * First step in the process of registering for a workshop.
	 *
	 * The already selected date is displayed along with 
	 * input fields to gather further required information.
	 *
	 * @param \NIMIUS\Workshops\Domain\Model\Date $date
	 * @dontvalidate $date
	 * @return void
	 */
	public function newAction(Date $date)
	{   
        $registration = $this->objectManager->get(Registration::class);
		$registration->setWorkshopDate($date);
		if ($this->currentFrontendUser()) {
			$registration->setFrontendUser($this->currentFrontendUser());
			$registration->populateFromFrontendUser();
		}
		$this->view->assignMultiple([
			'frontendUser' => $this->currentFrontendUser(),
			'date' => $date,
			'registration' => $registration,
			'workshop' => $date->getWorkshop()
        ]);
	}
	
	/**
	 * Initializer for create action.
	 *
	 * Modifies the request; Allows property "frontentUser" and sets 
	 * it to the currently logged in frontend user if one is present.
	 *
	 * Also sets the currently set language, which is required for 
	 * mails sent through the scheduler, in order to know in which
	 * language to send the mails.
	 *
	 * @return void
	 */
	protected function initializeCreateAction()
	{
		$propertyMappingConfiguration = $this->arguments['registration']->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->forProperty('additionalFields')->allowAllProperties();
		$arguments = $this->request->getArguments();
		
		if ($GLOBALS['TSFE']->sys_language_uid > 0) {
			$propertyMappingConfiguration->allowProperties('language');
			$arguments['registration']['language'] = $this->languageRepository->findByUid($GLOBALS['TSFE']->sys_language_uid);
		}
		
		if ($this->currentFrontendUser()) {
			$propertyMappingConfiguration->allowProperties('frontendUser');
			$arguments['registration']['frontendUser'] = $this->currentFrontendUser()->getUid();
		}
			
		$this->request->setArguments($arguments);
	}

	/**
	 * Second step in the registration process.
	 *
	 * Stores the given data as registration and redirects
	 * to the confirmation view.
	 *
	 * @param \NIMIUS\Workshops\Domain\Model\Registration $registration
	 * @return void
	 */
	public function createAction(Registration $registration)
	{
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'registrationsBeforeCreateAction', [$registration, $this]);

		$this->registrationRepository->add($registration);
		$this->persistenceManager->persistAll();
		$configuration = ConfigurationUtility::getTyposcriptConfiguration();
		if (!(bool)$configuration['registration.']['useScheduler']) {
			$this->registrationMailer->deliverRegistrationConfirmation($registration);
		}
        
        // Create a hmac from the given registration uid based on set encryption key.
		$registrationHmac = GeneralUtility::hmac($registration->getUid());
        $this->session->set('registrationHmac', $registrationHmac);

		$this->signalSlotDispatcher->dispatch(__CLASS__, 'registrationsAfterCreateAction', [$registration, $this]);
		$this->redirect('confirm', null, null, ['registration' => $registration]);
	}
	
	/**
	 * Confirmation view.
	 *
	 * After a successfull create, the attendee is redirected to
	 * this confirmation view.
	 *
	 * @param \NIMIUS\Workshops\Domain\Model\Registration $registration
	 * @return void
	 */
	public function confirmAction(Registration $registration)
	{
		$registrationHmac = GeneralUtility::hmac($registration->getUid());
        $storedHmac = $this->session->get('registrationHmac');
        if ($registrationHmac !== $storedHmac) {
            $GLOBALS['TSFE']->pageNotFoundAndExit('You are not allowed to access this resource.');
        }
        
        $this->view->assignMultiple([
			'frontendUser' => $this->currentFrontendUser(),
			'date' => $registration->getWorkshopDate(),
			'registration' => $registration,
			'workshop' => $registration->getWorkshopDate()->getWorkshop()
        ]);
	}

}