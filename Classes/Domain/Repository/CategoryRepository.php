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

/**
 * Category repository.
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository
{

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