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

use NIMIUS\Workshops\Domain\Proxy\CategoryRepositoryProxy;
use NIMIUS\Workshops\Persistence\Repository;

/**
 * Category repository.
 */
class CategoryRepository extends Repository
{

    /**
     * Find all categories matching the given proxy.
     *
     * @param \NIMIUS\Workshops\Domain\Proxy\CategoryRepositoryProxy $proxy
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findByProxy(CategoryRepositoryProxy $proxy)
    {
        $query = $this->createQuery();
        $constraints = [];
        parent::initializeQuery($query, $proxy);
        if ($proxy->getRootCategoriesOnly()) {
            $constraints[] = $query->equals('parent', 0);
        }
        if (count($constraints)) {
            $query->matching($query->logicalAnd($constraints));
        }
        return $query->execute();
    }

    /**
     * Find all categories without a parent.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */ 
    public function findAllRootCategories()
    {
        return $this->findAllChildren(0);
    }

    /**
     * Find all categories by given uids.
     *
     * @param array $uids
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findByUids(array $uids)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->in('uid', $uids)
        );
        return $query->execute();
    }

    /**
     * Find all child categories of the given parent.
     *
     * @param mixed $category Either a Category or a uid.
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
     */
    public function findAllChildren($category = 0)
    {
        $uid = is_int($category) ? $category : $category->getUid();
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('parent', $uid)
        )->execute();
    }

}
