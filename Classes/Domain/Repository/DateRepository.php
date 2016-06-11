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
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Date repository.
 */
class DateRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var array Setting for default ORDER BY when fetching records.
     */
    protected $defaultOrderings = [
        'beginAt' => QueryInterface::ORDER_ASCENDING,
    ];


    /**
     * Gets all upcoming dates for a given workshop.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @param integer                                 $timestamp
     * @param bool                                    $filterByEnd
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     */
    public function findAllUpcomingForWorkshop(Workshop $workshop, $timestamp = NULL, $filterByEnd = FALSE)
    {
        if (!$timestamp) {
            $timestamp = time();
        }

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $constraints = [
            $query->equals('workshop', $workshop),
        ];

        if ($filterByEnd) {
            $constraints[] = $query->greaterThanOrEqual('endAt', $timestamp);
        } else {
            $constraints[] = $query->greaterThanOrEqual('beginAt', $timestamp);
        }

        return $query->matching($query->logicalAnd($constraints))->execute();
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
    
    /**
     * Gets all current and upcoming dates for a given workshop.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     */
    public function findAllRelevantForWorkshop(Workshop $workshop)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        return $query->matching(
            $query->equals('workshop', $workshop),
            $query->logicalAnd(
                $query->logicalOr(
                    $query->greaterThanOrEqual('beginAt', time()),
                    $query->lessThanOrEqual('endAt', time())
                )
            )
        )->execute();
    }
    
    /**
     * Gets all upcoming dates.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     */
    public function findAllUpcoming($tstamp)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        return $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('beginAt', $tstamp)
            )
        )->execute();
    }

}