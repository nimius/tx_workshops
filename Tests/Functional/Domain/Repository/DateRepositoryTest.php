<?php
namespace NIMIUS\Workshops\Tests\Functional\Domain\Repository;

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

use NIMIUS\Workshops\Domain\Model\Date;
use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy;
use NIMIUS\Workshops\Domain\Repository\DateRepository;
use NIMIUS\Workshops\Domain\Repository\WorkshopRepository;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Date repository tests.
 */
class DateRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
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
    }

    /**
     * Test if findByProxy respects storage pid given.
     *
     * @test
     */
    public function findByProxyRespectsStoragePid()
    {
        $date = $this->createDate();
        $date->setWorkshop($this->createWorkshop());
        $date->setPid(8);
        $this->dateRepository->add($date);
        $this->persistenceManager->persistAll();
        
        $proxy = $this->createProxy();
        $proxy->setPid(8);
        $proxy->setHidePastDates(false);
        $proxy->setLanguages([]);
        
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 1);
    }

    /**
     * Test if findByProxy() respects hidePastDates.
     *
     * @test
     */
    public function findByProxyRespectsHidePastDates()
    {
        $date = $this->createDate();
        $date->setWorkshop($this->createWorkshop());
        $date->setEndAt(strtotime('-2 days'));
        $this->dateRepository->add($date);
        $this->persistenceManager->persistAll();
        
        $proxy = $this->createProxy();
        $proxy->setLanguages([]);

        $proxy->setHidePastDates(true);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 0);
        
        $proxy->setHidePastDates(false);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 1);
    }

    /**
     * Test if findByProxy() respects hideAlreadyStartedDates.
     *
     * @test
     */
    public function findByProxyRespectsHideAlreadyStartedDates()
    {
        $date = $this->createDate();
        $date->setWorkshop($this->createWorkshop());
        $date->setBeginAt(strtotime('-2 days'));
        $this->dateRepository->add($date);
        $this->persistenceManager->persistAll();
        
        $proxy = $this->createProxy();
        $proxy->setLanguages([]);

        $proxy->setHideAlreadyStartedDates(true);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 0);
        
        $proxy->setHideAlreadyStartedDates(false);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 1);
    }
    
    /**
     * Test if findByProxy() respects workshop languages.
     *
     * @test
     */
    public function findByProxyRespectsWorkshopLanguages()
    {
        $date = $this->createDate();
        $date->setWorkshop($this->createWorkshop());
        $date->setBeginAt(strtotime('+2 days'));
        $this->dateRepository->add($date);
        
        $workshop = $this->createWorkshop();
        $date = $this->createDate();
        $date->setWorkshop($workshop);
        $date->setBeginAt(strtotime('+2 days'));
        $this->dateRepository->add($date);
        $this->persistenceManager->persistAll();
        
        // As sys_language_uid is overwritten on handling, the uid is set "manually".
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_workshops_domain_model_workshop', 'uid = ' . $workshop->getUid(), ['sys_language_uid' => -1]);
        
        $proxy = $this->createProxy();
        $proxy->setLanguages([]);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 2);
        
        $proxy->setLanguages([0, -1]);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 2);
    }

    /**
     * Test if findByProxy() respects withinDaysFromNow.
     *
     * @test
     */
    public function findByProxyRespectsWithinDaysFromNow()
    {
        $date = $this->createDate();
        $date->setWorkshop($this->createWorkshop());
        $date->setBeginAt(strtotime('+2 days'));
        $this->dateRepository->add($date);
        
        $date = $this->createDate();
        $date->setWorkshop($this->createWorkshop());
        $date->setBeginAt(strtotime('+8 days'));
        $this->dateRepository->add($date);

        $date = $this->createDate();
        $date->setWorkshop($this->createWorkshop());
        $date->setBeginAt(strtotime('+1 year'));
        $this->dateRepository->add($date);

        $this->persistenceManager->persistAll();
        
        $proxy = $this->createProxy();
        $proxy->setLanguages([]);

        $proxy->setWithinDaysFromNow(4);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 1);
        
        $proxy->setWithinDaysFromNow(null);
        $dates = $this->dateRepository->findByProxy($proxy);
        $this->assertTrue(count($dates) == 3);
    }

    /**
     * Helper to create a proxy object.
     *
     * @return DateRepositoryProxy
     */
    protected function createProxy()
    {
        return $this->objectManager->get(DateRepositoryProxy::class);
    }

    /**
     * Helper to create a workshop object.
     *
     * @return Workshop
     */
    protected function createWorkshop()
    {
        $workshop = $this->objectManager->get(Workshop::class);
        $workshop->setPid(0);
        $this->workshopRepository->add($workshop);
        $this->persistenceManager->persistAll();
        return $workshop;
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