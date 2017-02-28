<?php
namespace NIMIUS\Workshops\Utility;

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
 * Utility class for TCA.
 */
class TCAUtility
{
    /**
     * Prepares an 'eval' value string for the given TCA field,
     * which is evaluated based on the user's TypoScript configuration.
     *
     * @param string $field
     * @return string
     */
    public static function registrationValidationEvalValue($field)
    {
        $value = [];
        $settings = ConfigurationUtility::getTyposcriptConfiguration()['registration.']['validation.'][$field . '.'];
        if ($settings) {
            foreach ($settings as $validation) {
                switch ($validation['validator']) {
                    case 'NotEmpty':
                        $value[] = 'required';
                        break;

                    case 'DateTime':
                        $value[] = 'datetime';
                        break;

                    case 'EmailAddress':
                        $value[] = 'email';
                        break;

                    case 'Float':
                        $value[] = 'float';
                        break;

                    case 'Integer':
                        $value[] = 'int';
                        break;

                    case 'Number':
                        $value[] = 'num';
                        break;
                }
            }
        }
        return implode(',', $value);
    }
}
