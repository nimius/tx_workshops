<?php
namespace NIMIUS\Workshops\Test\Functional\Service;

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

use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Service\WorkshopUrlService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Functional test case for WorkshopUrlService class.
 *
 * @todo Add tests for various cases (Currently throws "Call to a member function getPage_noCheck() on a non-object")
 */
class WorkshopUrlServiceTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{

    /**
     * @var array Required extensions for this test suite.
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/workshops'];

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \NIMIUS\Workshops\Service\WorkshopUrlService
     */
    protected $subject;

    /**
     * Test if render() returns an external url for external workshops.
     *
     * @test
     */
    public function renderReturnsAnExternalUrlForExternalWorkshops()
    {
        $workshop = $this->createWorkshop();
        $workshop->setType(Workshop::TYPE_EXTERNAL);
        $workshop->setExternalUrl('http://example.com');

        $this->subject->setObject($workshop);
        $url = $this->subject->render();
        $this->assertEquals($url, 'http://example.com');
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $this->objectManager->get(WorkshopUrlService::class);
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
}
