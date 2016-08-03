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
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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
     * Due to how localizations work, switching the language multiple
     * times does not work because it leads to all mails having the same
     * language as the first language processed, they are returned in badges
     * of the same language.
     *
     * @param integer $graceTime Grace time in seconds
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findAllWithoutSentConfirmation($graceTime = NULL)
    {
        $countQuery = $this->createQuery();
        $countQuery->statement('
            SELECT
                COUNT(uid) AS record_count, language
            FROM
                tx_workshops_domain_model_registration
            WHERE
                confirmation_sent_at = 0
            GROUP BY
                language
            ORDER BY
                record_count DESC
            LIMIT
                1
        ');
        $highestResult = $countQuery->execute(true)[0];

        $query = $this->createQuery();
        $constraints = [
            $query->equals('confirmationSentAt', 0),
            $query->equals('language', $highestResult['language'])
        ];
        if ($graceTime) {
            $constraints[] = $query->lessThanOrEqual('crdate', (time() + $graceTime));
        }
        $query->matching(
            $query->logicalAnd($constraints)
        );
        $query->setOrderings([
            'language' => QueryInterface::ORDER_DESCENDING
        ]);
        return $query->execute();
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
        );
        $query->setOrderings([
            'language' => QueryInterface::ORDER_ASCENDING
        ]);
        return $query->execute();
    }

}