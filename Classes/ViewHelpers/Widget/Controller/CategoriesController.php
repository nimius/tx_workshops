<?php
namespace NIMIUS\Workshops\ViewHelpers\Widget\Controller;

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
use NIMIUS\Workshops\Utility\ObjectUtility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Categories controller for "categories" widget.
 */
class CategoriesController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController
{

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository;

    /**
     * @var array tt_content record.
     */
    protected $contentRecord;


    /**
     * Default action for this widget controller.
     *
     * @return void
     */
    public function indexAction()
    {
        $categories = [];
        $proxy = $this->objectManager->get(CategoryRepositoryProxy::class);
        $pids = GeneralUtility::trimExplode(',', $this->contentRecord['pages'], true);
        if (count($pids)) {
            $proxy->setPids($pids);
        }

        $categoryUids = GeneralUtility::trimExplode(',', $this->settings['categories'], true);
        if (count($categoryUids)) {
            $proxy->setUids($categoryUids);
        } else {
            $proxy->setRootCategoriesOnly(true);
        }

        $rootCategories = $this->categoryRepository->findByProxy($proxy)->toArray();
        $this->fetchChildren($categories, $rootCategories);
        $this->view->assignMultiple([
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'pluginName' => $this->widgetConfiguration['pluginName'],
            'controllerName' => $this->widgetConfiguration['controllerName']
        ]);
    }

    /**
     * Recursively fetch child categories into an array.
     *
     * @param array &$collection Category collection to work on
     * @param array $categories Current level's categories to process
     * @return void
     */
    protected function fetchChildren(array &$collection, array $categories)
    {
        foreach ($categories as $category) {
            if ($this->categoryIsInCollection($collection, $category)) {
                continue;
            }

            $children = $this->categoryRepository->findAllChildren($category)->toArray();
            $collection[$category->getUid()] = [
                'category' => $category,
                'children' => []
            ];
            if (count($children) > 0) { 
                $this->fetchChildren($collection[$category->getUid()]['children'], $children);
            }
        }
    }

    /**
     * Check given collection recursively if the given category is already present.
     *
     * @param array $collection
     * @param \NIMIUS\Workshops\Domain\Model\Category $category
     * @return bool
     */
    protected function categoryIsInCollection(array $collection, $category)
    {
        $iterator = new \RecursiveArrayIterator($collection);
        $recursive = new \RecursiveIteratorIterator(
            $iterator,
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($recursive as $key => $value) {
            if ($key === $category->getUid()) {
                return true;
            }
        }
        return false;
    }


    /**
     * Widget action initializer.
     *
     * @return void
     */
    protected function initializeAction()
    {
        $this->contentRecord = ObjectUtility::getConfigurationManager()->getContentObject()->data;

        $pluginArguments = GeneralUtility::_GP('tx_workshops_' . strtolower($this->widgetConfiguration['pluginName']));
        if ((int)$pluginArguments['category']) {
            $activeCategory = $this->categoryRepository->findByUid((int)$pluginArguments['category']);
        }
        if (empty($this->widgetConfiguration['controllerName'])) {
            $this->widgetConfiguration['controllerName'] = $this->widgetConfiguration['pluginName'];
        }
    }

}