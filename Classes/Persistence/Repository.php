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
     * @param array &$constraints
     * @return void
     */
    protected function initializeQuery($query, $proxy, array &$constraints = [])
    {
        if (count($proxy->getPids()) > 0) {
            $this->setRespectStoragePageId($query, true);
            $this->setStoragePageIds($query, $proxy->getPids());
        } elseif ($proxy->getIgnoreStoragePid()) {
            $this->setRespectStoragePageId($query, false);
        }

        if ($proxy->getCategories()) {
            $this->buildCategoriesConstraints($proxy, $query, $constraints);
        }
    }

    /**
     * Helper method to set storage page ids for query constraints.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Query $query
     * @param integer $pid
     * @return void
     */
    protected function setStoragePageIds($query, $pids)
    {
        $query->getQuerySettings()->setStoragePageIds($pids);
    }

    /**
     * Helper to set constraint about respecting the storage page id.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Query $query
     * @param bool $respect
     * @return void
     */
    protected function setRespectStoragePageId($query, $respect = false)
    {
        $query->getQuerySettings()->setRespectStoragePage($respect);
    }

    /**
     * Builds category constraints for given proxy object.
     *
     * @param mixed $proxy
     * @param \TYPO3\CMS\Extbase\Persistence\Query $query
     * @param array &$constraints
     * @return void
     */
    protected function buildCategoriesConstraints($proxy, $query, &$constraints)
    {
        $className = array_pop(explode('\\', $query->getType()));
        switch($className) {
            case 'Workshop':
                $fieldName = 'categories';
                break;
            
            case 'Date':
                $fieldName = 'workshop.categories';
                break;
            
            default:
                return;
        }

        $categoriesConstraints = [];
        foreach($proxy->getCategories() as $category) {
            $categoriesConstraints[] = $query->contains($fieldName, $category);
        }
        if ($proxy->getCategoryOperator() == 'AND') {
            $constraints[] = $query->logicalAnd($categoriesConstraints);
        } else {
            $constraints[] = $query->logicalOr($categoriesConstraints);
        }
        unset($categoriesConstraints, $fieldName);
    }

}
