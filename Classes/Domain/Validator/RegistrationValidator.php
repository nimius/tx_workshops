<?php
namespace NIMIUS\Workshops\Domain\Validator;

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

use NIMIUS\Workshops\Utility\ObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Registration validator.
 */
class RegistrationValidator extends AbstractObjectValidator
{

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Registration
     */
    protected $registration;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     * @inject
     */
    protected $configurationManager;


    /**
     * Main method of the validator.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Registration $registration
     * @return void
     */
    public function isValid($registration)
    {
        $this->registration = $registration;
        $this->settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        $this->validateWorkshopDate();
        if ($this->settings['registration']['validation']) {
            $this->validateConfiguredValidations();
        }
    }

    /**
     * Validates plausibility of workshop date.
     *
     * @return void
     */
    protected function validateWorkshopDate()
    {
        $workshopDate = $this->registration->getWorkshopDate();
        
        if ($workshopDate->getParent()) {
            $this->addErrorToProperty('validator.registration.registrationForParentRequired', [], 'workshopDate', 1448716674);
        }

        if ($workshopDate->getSeatsAvailable() <= 0) {
            $this->addErrorToProperty('validator.registration.noSeatsAvailable', [], 'workshopDate', 1448124038);
        }
        if ($workshopDate->getRegistrationDeadlineReached()) {
            $this->addErrorToProperty('validator.registration.registrationDeadlineReached', [], 'workshopDate', 1448124040);
        }
        
        if ($workshopDate->getEndAt() < time()) {
            $this->addErrorToProperty('validator.registration.alreadyEnded', [], 'workshopDate', 1448124400);
        } elseif ($workshopDate->getBeginAt() < time()) {
            $this->addErrorToProperty('validator.registration.alreadyBegun', [], 'workshopDate', 1448124041);
        }
    }

    /**
     * Validates registration fields defined in TypoScript through validators
     * available to extbase.
     *
     * The following TypoScript example validates that the property "firstName"
     * is not empty and the string is longer than five chars.
     *
     *      plugin.tx_workshops.settings.registration.validation {
     *          firstName {
     *              10.validator = NotEmpty
     *              20.validator = StringLength
     *              20.options.minimum = 5
     *          }
     *          additionalFields {
     *              fieldName {
     *                  // ...
     *              }
     *          }
     *      }
     *
     * @todo refactor, remove duplicated code
     * @return void
     */
    protected function validateConfiguredValidations()
    {
        $resolver = ObjectUtility::get(\TYPO3\CMS\Extbase\Validation\ValidatorResolver::class);
        $validations = $this->settings['registration']['validation'];
        $additionalPropertyValidations = $validations['additionalFields'];
        unset($validations['additionalFields']);    
            
        foreach($validations as $property => $config) {
            foreach($config as $validation) {
                $validator = $resolver->createValidator($validation['validator'], (array)$validation['options']);
                $result = $validator->validate($this->registration->_getProperty($property));
                $this->mergeErrorsFromValidator($result, $property);
            }
        }
        
        if ($additionalPropertyValidations) {
            $propertyValues = $this->registration->getAdditionalFields();
            foreach($additionalPropertyValidations as $property => $config) {
                foreach($config as $validation) {
                    $validator = $resolver->createValidator($validation['validator'], (array)$validation['options']);
                    $result = $validator->validate($propertyValues[$property]);
                    $this->mergeErrorsFromValidator($result, 'additionalFields.' . $property);
                }
            }
        }
    }

}