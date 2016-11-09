<?php
namespace NIMIUS\Workshops\Domain\Proxy;

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

/**
 * Category repository proxy class.
 *
 * Proxy object for filtering categories in repositories.
 */
class CategoryRepositoryProxy extends AbstractRepositoryProxy
{

    /**
     * @var array Record uids.
     */
    protected $uids = [];

    /**
     * @var bool Only root categories.
     */
    protected $rootCategoriesOnly = false;

    /**
     * @param array $uids
     * @return void
     */
    public function setUids(array $uids)
    {
        $this->uids = $uids;
    }

    /**
     * @return array
     */
    public function getUids()
    {
        return $this->uids;
    }

    /**
     * @param bool $rootCategoriesOnly
     * @return void
     */
    public function setRootCategoriesOnly($rootCategoriesOnly)
    {
        $this->rootCategoriesOnly = $rootCategoriesOnly;
    }

    /**
     * @return bool
     */
    public function getRootCategoriesOnly()
    {
        return $this->rootCategoriesOnly;
    }
}
