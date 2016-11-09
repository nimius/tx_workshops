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

use NIMIUS\Workshops\Domain\Model\Location;
use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy;

/**
 * Unit test case for DateRepositoryProxy class.
 */
class DateRepositoryProxyTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{

    /**
     * @var \NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy
     */
    protected $subject;

    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('location', (new Location));
        $this->_testGetterAndSetterForProperty('location', null);
        $this->_testGetterAndSetterForProperty('workshop', (new Workshop));
        $this->_testGetterAndSetterForProperty('workshop', null);
        $this->_testGetterAndSetterForProperty('recordLimit', 8);
        $this->_testGetterAndSetterForProperty('hideChildDates', true);
        $this->_testGetterAndSetterForProperty('hideChildDates', false);
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new DateRepositoryProxy;
    }
}
