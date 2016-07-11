<?php
namespace NIMIUS\Workshops\Persistence;

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

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * Repository class.
 *
 * Extends core repository class and adds functionality.
 */
class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Helper method to set storage page id for query constraints.
     *
     * @param integer $pid
     * @return void
     */
    protected function setStoragePageId($pid)
    {
        if ($pid) {
            /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
            $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
            $querySettings->setStoragePageIds([$pid]);
            $this->setDefaultQuerySettings($querySettings);
        }
    }

}