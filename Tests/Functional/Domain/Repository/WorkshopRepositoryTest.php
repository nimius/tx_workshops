<?php
namespace NIMIUS\Workshops\Tests\Functional\Domain\Repository;

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

use NIMIUS\Workshops\Domain\Model\Category;
use NIMIUS\Workshops\Domain\Model\Date;
use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy;
use NIMIUS\Workshops\Domain\Repository\CategoryRepository;
use NIMIUS\Workshops\Domain\Repository\DateRepository;
use NIMIUS\Workshops\Domain\Repository\WorkshopRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Workshop repository tests.
 */
class WorkshopRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    /**
     * @var array Required extensions for this test suite
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/workshops'];

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persisteneManager;

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\WorkshopRepository
     */
    protected $workshopRepository;

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\DateRepository
     */
    protected $dateRepository;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Workshop
     */
    protected $workshop;

    /**
     * Test case constructor / initializer.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
        $this->persistenceManager = $this->objectManager->get(PersistenceManager::class);
        $this->workshopRepository = $this->objectManager->get(WorkshopRepository::class);
        $this->dateRepository = $this->objectManager->get(DateRepository::class);
        $this->categoryRepository = $this->objectManager->get(CategoryRepository::class);
    }

    /**
     * Test if findByProxy respects storage pid given.
     *
     * @test
     */
    public function findByProxyRespectsStoragePid()
    {
        $workshop = $this->createWorkshop();
        $workshop->setPid(2);
        $this->workshopRepository->add($workshop);
        $this->persistenceManager->persistAll();

        $proxy = $this->createProxy();
        $proxy->setPid(2);

        $workshops = $this->workshopRepository->findByProxy($proxy)->toArray();
        $this->assertTrue(count($workshops) == 1);
    }

    /**
     * Test if findByProxy respects categories given.
     *
     * @test
     */
    public function findByProxyRespectsCategories()
    {
        $category = $this->createCategory();
        $this->categoryRepository->add($category);

        $workshop1 = $this->createWorkshop();
        $workshop1->addCategory($category);
        $this->workshopRepository->add($workshop1);

        $workshop2 = $this->createWorkshop();
        $this->workshopRepository->add($workshop2);

        $this->persistenceManager->persistAll();

        $proxy = $this->createProxy();
        $proxy->setCategories([$category]);

        $workshops = $this->workshopRepository->findByProxy($proxy)->toArray();
        $this->assertTrue(count($workshops) == 1);
    }

    /**
     * Test if findByProxy respects the "OR" category operator given.
     *
     * @test
     */
    public function findByProxyRespectsORCategoryOperator()
    {
        $category1 = $this->createCategory();
        $this->categoryRepository->add($category1);

        $category2 = $this->createCategory();
        $this->categoryRepository->add($category2);

        $workshop1 = $this->createWorkshop();
        $workshop1->addCategory($category1);
        $this->workshopRepository->add($workshop1);

        $workshop2 = $this->createWorkshop();
        $workshop2->addCategory($category2);
        $this->workshopRepository->add($workshop2);

        $this->persistenceManager->persistAll();

        $proxy = $this->createProxy();
        $proxy->setCategories([$category1, $category2]);
        $proxy->setCategoryOperator('OR');

        $workshops = $this->workshopRepository->findByProxy($proxy)->toArray();
        $this->assertTrue(count($workshops) == 2);
    }

    /**
     * Test if findByProxy respects the "AND" category operator given.
     *
     * @test
     */
    public function findByProxyRespectsANDCategoryOperator()
    {
        $category1 = $this->createCategory();
        $this->categoryRepository->add($category1);

        $category2 = $this->createCategory();
        $this->categoryRepository->add($category2);

        $workshop1 = $this->createWorkshop();
        $workshop1->addCategory($category1);
        $this->workshopRepository->add($workshop1);

        $workshop2 = $this->createWorkshop();
        $workshop2->addCategory($category2);
        $this->workshopRepository->add($workshop2);

        $workshop3 = $this->createWorkshop();
        $workshop3->addCategory($category1);
        $workshop3->addCategory($category2);
        $this->workshopRepository->add($workshop3);

        $this->persistenceManager->persistAll();

        $proxy = $this->createProxy();
        $proxy->setCategories([$category1, $category2]);
        $proxy->setCategoryOperator('AND');

        $workshops = $this->workshopRepository->findByProxy($proxy)->toArray();
        $this->assertTrue(count($workshops) == 1);
    }

    /**
     * Test if findByProxy respects hiding workshops without upcoming date option.
     *
     * @test
     */
    public function findByProxyRespectsHideWorkshopsWithoutUpcomingDate()
    {
        $workshop1 = $this->createWorkshop();
        $this->workshopRepository->add($workshop1);

        $workshop2 = $this->createWorkshop();
        $date1 = $this->createDate();
        $date1->setBeginAt(time() + 3600);
        $workshop2->addDate($date1);
        $this->workshopRepository->add($workshop2);

        $workshop3 = $this->createWorkshop();
        $date2 = $this->createDate();
        $date2->setBeginAt(time() - 3600);
        $workshop3->addDate($date2);
        $this->workshopRepository->add($workshop3);

        $this->persistenceManager->persistAll();

        $proxy = $this->createProxy();
        $proxy->setHideWorkshopsWithoutUpcomingDates(true);

        $workshops = $this->workshopRepository->findByProxy($proxy)->toArray();
        $this->assertTrue(count($workshops) == 1);
    }

    /**
     * Helper to create a proxy object.
     *
     * @return WorkshopRepositoryProxy
     */
    protected function createProxy()
    {
        return $this->objectManager->get(WorkshopRepositoryProxy::class);
    }

    /**
     * Helper to create a workshop object.
     *
     * @return Workshop
     */
    protected function createWorkshop()
    {
        return $this->objectManager->get(Workshop::class);
    }

    /**
     * Helper to create a category object.
     *
     * @return Category
     */
    protected function createCategory()
    {
        return $this->objectManager->get(Category::class);
    }

    /**
     * Helper to create a date object.
     *
     * @return Category
     */
    protected function createDate()
    {
        return $this->objectManager->get(Date::class);
    }
}
