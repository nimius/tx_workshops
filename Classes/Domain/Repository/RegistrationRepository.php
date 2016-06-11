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

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * Registration repository.
 */
class RegistrationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Repository object initializer.
     *
     * Disable storage pid constraint for queries as parts accessing registrations
     * do not rely on a storage pid (e.g. scheduler).
     *
     * @return void
     */
    public function initializeObject()
    {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(FALSE);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Find all registrations not having a confirmation sent to.
     *
     * @param integer $graceTime Grace time in seconds
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findAllWithoutSentConfirmation($graceTime = NULL)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('confirmationSentAt', 0);
        if ($graceTime) {
            $constraints[] = $query->lessThanOrEqual('crdate', (time() + $graceTime));
        }
        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    /**
     * Find all registrations newer than the given timestamp.
     *
     * @param integer $timestamp
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findAllCreatedSince($timestamp)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->greaterThanOrEqual('crdate', $timestamp)
        )->execute();
    }

}