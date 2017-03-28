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
use NIMIUS\Workshops\Utility\ObjectUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

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

    /**
     * Returns the uids of workshops that are available in the given languages.
     * The second argument is the instance of ContentObjectRenderer that should be used.
     * This argument is only used for easier stubbing in tests and can be ignored for regular use.
     *
     * @param int[] $languages
     * @param ContentObjectRenderer $cObj
     * @return int[]
     */
    public function getWorkshopUidsMatchingLanguages($languages, $cObj = NULL) {
        if ($cObj === NULL) {
            $cObj = ObjectUtility::getConfigurationManager()->getContentObject();
        }

        $enableFields = $cObj->enableFields('tx_workshops_domain_model_workshop');

        $query = '
         SELECT DISTINCT
            CASE
                WHEN l10n_parent != 0 THEN l10n_parent
                ELSE uid
            END as uid
         FROM tx_workshops_domain_model_workshop 
         WHERE sys_language_uid IN ( '. implode(',', $languages) .' )' . $enableFields;

        $result = $this->createQuery()->statement($query)->execute(TRUE);
        return array_map(function($a) {
            return (int)$a['uid'];
        }, $result);
    }
}
