<?php
namespace NIMIUS\Workshops\Domain\Proxy;

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
 * Workshop repository proxy class.
 *
 * Proxy object for filtering workshops in repositories.
 */
class WorkshopRepositoryProxy
{

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository;

    /**
     * @var integer Storage page id
     */
    protected $pid;

    /**
     * @var mixed A traversable object containing \NIMIUS\Workshops\Domain\Model\Category records
     */
    protected $categories;

    /**
     * @var string Category operator (AND, OR, ...)
     */
    protected $categoryOperator;

    /**
     * @var string Sorting field
     */
    protected $sortingField = 'sorting';

    /**
     * @var string Sorting type
     */
    protected $sortingType = 'ASC';

    /**
     * @var bool Hide workshops not having an upcoming date
     */
    protected $hideWorkshopsWithoutUpcomingDates = FALSE;


    /**
     * @param array $settings
     * @return void
     */
    public function initializeFromSettings($settings)
    {
        if (!empty($settings['categoryOperator'])) {
            $this->setCategoryOperator = $settings['categoryOperator'];

            // Categories can only be selected if a proper operator is set
            $categoriesUids = explode(',', $settings['categories']);
            $this->setCategoriesUids($categoriesUids);
        }
        unset($settings['categoryOperator'], $settings['categories']);

        foreach($settings as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param integer $pid
     * @return void
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     * @return void
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @param array $uids Array of uid's
     * @return void
     */
    public function setCategoriesUids($uids)
    {
        $this->categories = [];
        foreach ($uids as $uid) {
            $this->categories[] = $this->categoryRepository->findByUid((int)$uid);
        }
    }

    /**
     * @return mixed
     */
    public function getCategoryOperator()
    {
        return $this->categoryOperator;
    }

    /**
     * @param string $categoryOperator
     * @return void
     */
    public function setCategoryOperator($categoryOperator)
    {
        $this->categoryOperator = $categoryOperator;
    }

    /**
     * @return string
     */
    public function getSortingField()
    {
        return $this->sortingField;
    }

    /**
     * @param string $sortingField
     * @return void
     */
    public function setSortingField($sortingField)
    {
        $this->sortingField = $sortingField;
    }

    /**
     * @return string
     */
    public function getSortingType()
    {
        return $this->sortingType;
    }

    /**
     * @param string $sortingType
     * @return void
     */
    public function setSortingType($sortingType)
    {
        $this->sortingType = $sortingType;
    }

    /**
     * @return bool
     */
    public function getHideWorkshopsWithoutUpcomingDates()
    {
        return $this->hideWorkshopsWithoutUpcomingDates;
    }

    /**
     * @param bool $hide
     * @return void
     */
    public function setHideWorkshopsWithoutUpcomingDates($hide)
    {
        $this->hideWorkshopsWithoutUpcomingDates = $hide;
    }

}