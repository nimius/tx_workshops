<?php
namespace NIMIUS\Workshops\Domain\Repository;

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

use NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy;
use NIMIUS\Workshops\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Workshop repository.
 */
class WorkshopRepository extends Repository
{

    /**
     * @var array Setting for default ORDER BY when fetching records.
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];


    /**
     * Find all workshops matching the given proxy.
     *
     * @param \NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy $proxy
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findByProxy(WorkshopRepositoryProxy $proxy)
    {
        $constraints = [];

        if ($proxy->getPid()) {
            $this->setStoragePageId($proxy->getPid());
        }

        $query = $this->createQuery();
        
        if ($proxy->getCategories()) {
            $categoriesConstraints = [];
            foreach($proxy->getCategories() as $category) {
                $categoriesConstraints[] = $query->contains('categories', $category);
            }
            if ($proxy->getCategoryOperator() == 'AND') {
                $constraints[] = $query->logicalAnd($categoriesConstraints);
            } else {
                $constraints[] = $query->logicalOr($categoriesConstraints);
            }
            unset($categoriesConstraints);
        }
        if ($proxy->getHideWorkshopsWithoutUpcomingDates()) {
            $constraints[] = $query->logicalAnd(
                $query->greaterThanOrEqual('dates.beginAt', time())
            );
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd($constraints));
        }

        $query->setOrderings([$proxy->getSortingField() => $proxy->getSortingType()]);
        return $query->execute();
    }

    /**
     * Find all workshops.
     *
     * Overrides already present findAll() from parent class to
     * enable scoping by page uid.
     *
     * @param integer $pid Page uid to constrain queries to.
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findAll($pid = NULL)
    {
        $this->setStoragePageId($pid);
        return parent::findAll();
    }

}