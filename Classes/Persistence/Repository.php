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
     * Query initializer.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Query $query
     * @param mixed $proxy
     * @return void
     */
    protected function initializeQuery($query, $proxy)
    {
        if ($proxy->getPid()) {
            $this->setStoragePageId($query, $proxy->getPid());
        } elseif ($proxy->getIgnoreStoragePid()) {
            $this->setRespectStoragePageId($query, FALSE);
        }
    }

    /**
     * Helper method to set storage page id for query constraints.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Query $query
     * @param integer $pid
     * @return void
     */
    protected function setStoragePageId($query, $pid)
    {
        $query->getQuerySettings()->setStoragePageIds([$pid]);
    }

    /**
     * Helper to set constraint about respecting the storage page id.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Query $query
     * @param bool $respect
     * @return void
     */
    protected function setRespectStoragePageId($query, $respect = FALSE)
    {
        $query->getQuerySettings()->setRespectStoragePage($respect);
    }

}