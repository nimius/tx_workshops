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

use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy;
use NIMIUS\Workshops\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Date repository.
 */
class DateRepository extends Repository
{

    /**
     * @var array Setting for default ORDER BY when fetching records.
     */
    protected $defaultOrderings = [
        'beginAt' => QueryInterface::ORDER_ASCENDING,
    ];


    /**
     * Find all dates matching the given proxy.
     *
     * @param \NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy $proxy
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findByProxy(DateRepositoryProxy $proxy)
    {
        $query = $this->createQuery();
        parent::initializeQuery($query, $proxy);

        $constraints = [];
        $beginOfToday = strtotime('today midnight');

        if ($proxy->getHidePastDates()) {
            $constraints[] = $query->greaterThanOrEqual('endAt', $beginOfToday);
        }
        if ($proxy->getHideAlreadyStartedDates()) {
            $constraints[] = $query->greaterThanOrEqual('beginAt', $beginOfToday);
        }
        if ($proxy->getWithinDaysFromNow()) {
            $withinDays = $beginOfToday + (int)$proxy->getWithinDaysFromNow() * 60 * 60 * 24;
            $constraints[] = $query->lessThanOrEqual('beginAt', $withinDays);
        }
        if ($proxy->getWorkshop()) {
            $constraints[] = $query->equals('workshop', $proxy->getWorkshop());
        }
        if ($proxy->getLocation()) {
            $constraints[] = $query->equals('location', $proxy->getLocation());
        }
        if ($proxy->getCategories()) {
            $categoriesConstraints = [];
            foreach($proxy->getCategories() as $category) {
                $categoriesConstraints[] = $query->contains('workshop.categories', $category);
            }
            if ($proxy->getCategoryOperator() == 'AND') {
                $constraints[] = $query->logicalAnd($categoriesConstraints);
            } else {
                $constraints[] = $query->logicalOr($categoriesConstraints);
            }
            unset($categoriesConstraints);
        }
        if ($proxy->getHideChildDates()) {
            // Child dates obviously have a parent set.
            $constraints[] = $query->equals('parent', 0);

            // Additional failproofing if dates are available without a
            // valid workshop, or a workshop had dates and switched type.
            $constraints[] = $query->greaterThan('workshop.uid', 0);
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd($constraints));
        }
        if ((int)$proxy->getRecordLimit() > 0) {
            $query->setLimit($proxy->getRecordLimit());
        }
        return $query->execute();
    }

    /**
     * Gets the next upcoming dates for a given workshop.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @param integer $tstamp
     * @return mixed
     */
    public function findNextUpcomingForWorkshop(Workshop $workshop)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        return $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('beginAt', time()),
                $query->equals('workshop', $workshop)
            )
        )->setLimit(1)->execute()->getFirst();
    }

}