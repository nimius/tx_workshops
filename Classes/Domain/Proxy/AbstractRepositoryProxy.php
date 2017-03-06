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
 * Abstract repository proxy class.
 *
 * Contains shared properties and methods.
 */
abstract class AbstractRepositoryProxy
{
    /**
     * @var \NIMIUS\Workshops\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository;

    /**
     * @var array Storage page ids.
     */
    protected $pids = [];

    /**
     * @var array Language uids.
     */
    protected $languages = [];

    /**
     * @var bool Set to ignore storage pid constraints.
     */
    protected $ignoreStoragePid = false;

    /**
     * @var mixed A traversable object containing \NIMIUS\Workshops\Domain\Model\Category records.
     */
    protected $categories;

    /**
     * @var string|null Category operator (AND, OR, ...).
     */
    protected $categoryOperator;

    /**
     * @var bool If set, child categories are also included in queries.
     */
    protected $recursiveCategorySelection = false;

    /**
     * @var string Sorting field.
     */
    protected $sortingField = 'sorting';

    /**
     * @var string Sorting type.
     */
    protected $sortingType = 'ASC';

    /**
     * @var int Restrict dates to be within the following amount of days from now
     */
    protected $withinDaysFromNow;

    /**
     * @var bool Hide dates being in the past, regardless of time.
     */
    protected $hidePastDates;

    /**
     * @var bool Hide dates where workshops already started regarding time.
     */
    protected $hideAlreadyStartedDates;

    /**
     * Constructor.
     *
     * Initializes class instance with default values.
     */
    public function __construct()
    {
        $this->languages = [-1, (int)$GLOBALS['TSFE']->sys_language_uid];
    }

    /**
     * Initialize proxy properties by given settings.
     *
     * Settings are coming from e.g. TypoScript or FlexForm.
     *
     * @param array $settings
     * @return void
     */
    public function initializeFromSettings(array $settings)
    {
        if (!empty($settings['categories'])) {
            $categoriesUids = explode(',', $settings['categories']);
            $this->setCategoriesUids($categoriesUids);
        }
        if (!empty($settings['categoryOperator'])) {
            $this->setCategoryOperator($settings['categoryOperator']);
        }
        if ((int)$settings['upcomingDays'] > 0) {
            $this->setWithinDaysFromNow((int)$settings['upcomingDays']);
        }
        if ((bool)$settings['hidePastDates']) {
            $this->setHidePastDates(true);
        }
        if ((bool)$settings['hideAlreadyStartedDates']) {
            $this->setHideAlreadyStartedDates(true);
        }
        // Unset already processed settings.
        unset(
            $settings['categories'], $settings['categoryOperator'], $settings['upcomingDays'],
            $settings['hidePastDates'], $settings['hideAlreadyStartedDates']
        );

        foreach ($settings as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function getPids()
    {
        return $this->pids;
    }

    /**
     * @param int $pid
     * @return void
     */
    public function setPid($pid)
    {
        $this->setPids([$pid]);
    }

    /**
     * @param array $pids
     * @return void
     */
    public function setPids($pids)
    {
        $this->pids = $pids;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param array $languages
     * @return void
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
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
     * @param \NIMIUS\Workshops\Domain\Model\Category $category
     * @return void
     */
    public function addCategory($category)
    {
        $this->categories[] = $category;
    }

    /**
     * @param array $uids Array of uid's
     * @return void
     */
    public function setCategoriesUids(array $uids)
    {
        $this->categories = [];
        foreach ($uids as $uid) {
            $this->categories[] = $this->categoryRepository->findByUid((int)$uid);
        }
    }

    /**
     * @return string|null
     */
    public function getCategoryOperator()
    {
        return $this->categoryOperator;
    }

    /**
     * @param string|null $categoryOperator
     * @return void
     */
    public function setCategoryOperator($categoryOperator)
    {
        $this->categoryOperator = $categoryOperator;
    }

    /**
     * @param bool $recursiveCategorySelection
     * @return void
     */
    public function setRecursiveCategorySelection($recursiveCategorySelection)
    {
        $this->recursiveCategorySelection = $recursiveCategorySelection;
    }

    /**
     * @return bool
     */
    public function getRecursiveCategorySelection()
    {
        return $this->recursiveCategorySelection;
    }

    /**
     * @return string|null
     */
    public function getSortingField()
    {
        return $this->sortingField;
    }

    /**
     * @param string|null $sortingField
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
     * @param string|null $sortingType
     * @return void
     */
    public function setSortingType($sortingType)
    {
        $this->sortingType = $sortingType;
    }

    /**
     * @return string|null
     */
    public function getWithinDaysFromNow()
    {
        return $this->withinDaysFromNow;
    }

    /**
     * @param int|null $withinDaysFromNow
     * @return void
     */
    public function setWithinDaysFromNow($withinDaysFromNow)
    {
        $this->withinDaysFromNow = $withinDaysFromNow;
    }

    /**
     * @return bool
     */
    public function getHidePastDates()
    {
        return $this->hidePastDates;
    }

    /**
     * @param bool $hidePastDates
     * @return void
     */
    public function setHidePastDates($hidePastDates)
    {
        $this->hidePastDates = (bool)$hidePastDates;
    }

    /**
     * @return bool
     */
    public function getHideAlreadyStartedDates()
    {
        return $this->hideAlreadyStartedDates;
    }

    /**
     * @param bool $hideAlreadyStartedDates
     * @return void
     */
    public function setHideAlreadyStartedDates($hideAlreadyStartedDates)
    {
        $this->hideAlreadyStartedDates = (bool)$hideAlreadyStartedDates;
    }

    /**
     * @return bool
     */
    public function getIgnoreStoragePid()
    {
        return $this->ignoreStoragePid;
    }

    /**
     * @param bool $ignoreStoragePid
     * @return void
     */
    public function setIgnoreStoragePid($ignoreStoragePid)
    {
        $this->ignoreStoragePid = (bool)$ignoreStoragePid;
    }
}
