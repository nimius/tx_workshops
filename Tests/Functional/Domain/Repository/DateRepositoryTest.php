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

        $this->workshop = $this->objectManager->get(Workshop::class);
        $this->workshopRepository->add($this->workshop);
        $this->persistenceManager->persistAll();
    }

    /**
     * Test if findAllUpcomingForWorkshop() does not return past single dates.
     *
     * @test
     */
    public function findAllUpcomingForWorkshopDoesNotReturnPastSingleDates()
    {
        $date = $this->objectManager->get(Date::class);
        $date->setWorkshop($this->workshop);
        $date->setBeginAt(time() - 100);
        $this->dateRepository->add($date);
        $this->persistenceManager->persistAll();
        $tstamp = time();

        $upcomingDates = $this->dateRepository->findAllUpcomingForWorkshop($this->workshop, $tstamp)->toArray();
        $this->assertTrue(count($upcomingDates) == 0);
    }

    /**
     * Test if findAllUpcomingForWorkshop() does return future single dates.
     *
     * @test
     */
    public function findAllUpcomingForWorkshopReturnsFutureSingleDates()
    {
        $date = $this->objectManager->get(Date::class);
        $date->setWorkshop($this->workshop);
        $date->setBeginAt(time() + 100);
        $this->dateRepository->add($date);
        $this->persistenceManager->persistAll();
        $tstamp = time();

        $upcomingDates = $this->dateRepository->findAllUpcomingForWorkshop($this->workshop, $tstamp)->toArray();
        $this->assertTrue(count($upcomingDates) == 1);
    }

    /**
     * Test if findAllUpcomingForWorkshop() does not return past group/multiple dates.
     *
     * @test
     */
    public function findAllUpcomingForWorkshopDoesNotReturnPastGroupDates()
    {
        $dateGroup = $this->objectManager->get(Date::class);
        $dateGroup->setWorkshop($this->workshop);
        $dateGroup->setType(Date::TYPE_MULTIPLE);
        $date = $this->objectManager->get(Date::class);
        $date->setBeginAt(time() - 100);
        $dateGroup->addDate($date);
        $this->dateRepository->add($dateGroup);
        $this->persistenceManager->persistAll();
        $tstamp = time();
        
        $upcomingDates = $this->dateRepository->findAllUpcomingForWorkshop($this->workshop, $tstamp)->toArray();
        $this->assertTrue(count($upcomingDates) == 0);
    }

}