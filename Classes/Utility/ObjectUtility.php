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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility class to get objects.
 *
 * As certain objects (e.g. objectManager) are only injected
 * and present in certain places (e.g. controllers), this class
 * makes it simpler to get them in various places.
 */
class ObjectUtility
{
    /**
     * Get object manager instance.
     *
     * @return \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    public static function getObjectManager()
    {
        return GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
    }

    /**
     * Get configuration manager instance.
     *
     * @return \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     */
    public static function getConfigurationManager()
    {
        return self::getObjectManager()->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
    }

    /**
     * Get an instance of the given class.
     *
     * @todo should also take params and pass along
     * @param string $className
     * @return mixed
     */
    public static function get($className)
    {
        return self::getObjectManager()->get($className);
    }
}
