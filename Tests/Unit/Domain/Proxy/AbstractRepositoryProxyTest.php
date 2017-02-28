<?php
namespace NIMIUS\Workshops\Test\Unit\Domain\Proxy;

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

// Manually requiring custom class as it is not autoloaded in the bootstrap process.
require_once __DIR__ . '/../../../AbstractUnitTestCase.php';

use NIMIUS\Workshops\Domain\Proxy\AbstractRepositoryProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Unit test case for AbstractRepositoryProxy class.
 */
class AbstractRepositoryProxyTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{
    /**
     * @var \object Mock of AbstractRepositoryProxy
     */
    protected $subject;

    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('pids', []);
        $this->_testGetterAndSetterForProperty('pids', [4, 71]);
        $this->_testGetterAndSetterForProperty('ignoreStoragePid', true);
        $this->_testGetterAndSetterForProperty('ignoreStoragePid', false);
        $this->_testGetterAndSetterForProperty('categories', (new ObjectStorage));
        $this->_testGetterAndSetterForProperty('categories', []);
        $this->_testGetterAndSetterForProperty('categoryOperator', 'AND');
        $this->_testGetterAndSetterForProperty('categoryOperator', null);
        $this->_testGetterAndSetterForProperty('sortingField', 'uid');
        $this->_testGetterAndSetterForProperty('sortingField', null);
        $this->_testGetterAndSetterForProperty('sortingType', 'DESC');
        $this->_testGetterAndSetterForProperty('sortingType', null);
        $this->_testGetterAndSetterForProperty('withinDaysFromNow', 10);
        $this->_testGetterAndSetterForProperty('withinDaysFromNow', null);
        $this->_testGetterAndSetterForProperty('hidePastDates', true);
        $this->_testGetterAndSetterForProperty('hidePastDates', false);
        $this->_testGetterAndSetterForProperty('hideAlreadyStartedDates', true);
        $this->_testGetterAndSetterForProperty('hideAlreadyStartedDates', false);
        $this->_testGetterAndSetterForProperty('ignoreStoragePid', true);
        $this->_testGetterAndSetterForProperty('ignoreStoragePid', false);
    }

    /**
     * Test if initializeFromSettings() initializes correctly from given settings.
     *
     * Watch out to not do testing on a functional level.
     *
     * @test
     */
    public function initializeFromSettingsInitializesGivenSettingsCorrectly()
    {
        $settings = [
            'categoryOperator' => 'AND',
            'upcomingDays' => 12,
            'hidePastDates' => true,
            'hideAlreadyStartedDates' => true,
            'pids' => [1, 2]
        ];
        $this->subject->initializeFromSettings($settings);
        $this->assertEquals($settings['categoryOperator'], $this->subject->getCategoryOperator());
        $this->assertEquals($settings['upcomingDays'], $this->subject->getWithinDaysFromNow());
        $this->assertEquals($settings['hidePastDates'], $this->subject->getHidePastDates());
        $this->assertEquals($settings['hideAlreadyStartedDates'], $this->subject->getHideAlreadyStartedDates());
        $this->assertEquals($settings['pids'], $this->subject->getPids());
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = $this->getMockForAbstractClass(AbstractRepositoryProxy::class);
    }
}
