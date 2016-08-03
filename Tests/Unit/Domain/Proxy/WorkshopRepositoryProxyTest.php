<?php
namespace NIMIUS\Workshops\Test\Unit\Domain\Proxy;

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

// Manually requiring custom class as it is not autoloaded in the bootstrap process.
require_once __DIR__ . '/../../../AbstractUnitTestCase.php';

use NIMIUS\Workshops\Domain\Model\Location;
use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy;

/**
 * Unit test case for WorkshopRepositoryProxy class.
 */
class WorkshopRepositoryProxyTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{

    /**
     * @var \NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy
     */
    protected $subject;


    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('hideWorkshopsWithoutUpcomingDates', true);
        $this->_testGetterAndSetterForProperty('hideWorkshopsWithoutUpcomingDates', false);
        $this->_testGetterAndSetterForProperty('types', []);
        $this->_testGetterAndSetterForProperty('types', [Workshop::TYPE_DEFAULT]);
    }


    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new WorkshopRepositoryProxy;
    }

}