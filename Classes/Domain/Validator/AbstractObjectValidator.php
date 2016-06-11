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

use TYPO3\CMS\Extbase\Validation\Error;

/**
 * Abstract object validator.
 *
 * Contains shared functionality across this extension's validators.
 * This requires the property "$subject" to be set.
 */
abstract class AbstractObjectValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;
    
    /**
     * Add an error message for the given property.
     *
     * @param string $error
     * @param string $property
     * @return void
     */
    protected function addErrorToProperty($error, $values = [], $property, $tstamp)
    {
        $error = $this->objectManager->get(
            Error::class,
            $this->translateErrorMessage($error, 'workshops', $values),
            $tstamp
        );
        $this->result->forProperty($property)->addError($error);
    }

    /**
     * Merge errors from given validator object into current object's base errors
     * or the given property, if it exists.
     *
     * @param mixed $validator
     * @param string $property
     * @return void
     */
    protected function mergeErrorsFromValidator($validator, $property = NULL)
    {
        foreach($validator->getErrors() as $error) {
            if ($property) {
                $this->result->forProperty($property)->addError($error);
            } else {
                $this->result->addError($error);
            }
        }
    }

}