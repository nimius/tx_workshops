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
     * Default action for this widget controller.
     *
     * @return void
     */
    public function indexAction()
    {
        $categories = [];
        $pluginArguments = GeneralUtility::_GP('tx_workshops_workshops');
        if ((int)$pluginArguments['category']) {
            $activeCategory = $this->categoryRepository->findByUid((int)$pluginArguments['category']);
        }
        $rootCategories = $this->categoryRepository->findAllRootCategories()->toArray();
        $this->fetchChildren($categories, $rootCategories);
        $this->view->assignMultiple([
            'categories' => $categories,
            'activeCategory' => $activeCategory
        ]);
    }


    /**
     * Method to recursively fetch child categories into an array.
     *
     * @param array &$collection Category collection to work on
     * @param array $categories Current level's categories to process
     * @return void
     */
    protected function fetchChildren(&$collection, $categories)
    {
        foreach ($categories as $category) {
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

}