<?php
namespace NIMIUS\Workshops\Domain\Model;

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

/**
 * Language model.
 *
 * Represents sys_language.
 */
class Language extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var string 2 char ISO code.
     */
    protected $languageIsocode;


    /**
     * @return string
     */
    public function getLanguageIsoCode() {
        return $this->languageIsocode;
    }

}