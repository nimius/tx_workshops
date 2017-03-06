<?php
namespace NIMIUS\Workshops\Domain\Model;

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
 * Frontend user model
 */
class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
    /**
     * @return string
     */
    public function getFullName()
    {
        if (empty($this->name)) {
            $parts = [
                $this->firstName,
                $this->middleName,
                $this->lastName
            ];
            return implode(' ', array_filter($parts, 'strlen'));
        } else {
            return $this->name;
        }
    }
}
