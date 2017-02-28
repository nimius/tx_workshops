<?php
namespace NIMIUS\Workshops\Domain\Repository;

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
        $query = $this->createQuery();
        $constraints = [];
        parent::initializeQuery($query, $proxy, $constraints);

        if (count($proxy->getTypes()) > 0) {
            $constraints[] = $query->in('type', $proxy->getTypes());
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
}
