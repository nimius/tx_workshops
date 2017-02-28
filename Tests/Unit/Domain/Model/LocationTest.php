<?php
namespace NIMIUS\Workshops\Test\Unit\Domain\Model;

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
use SJBR\StaticInfoTables\Domain\Model\Country;

/**
 * Unit test case for Location model.
 */
class LocationTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{
    /**
     * @var \NIMIUS\Workshops\Domain\Model\Location
     */
    protected $subject;

    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('name', 'Test name');
        $this->_testGetterAndSetterForProperty('address', 'Test address');
        $this->_testGetterAndSetterForProperty('zip', 'Test zip');
        $this->_testGetterAndSetterForProperty('city', 'Test city');
        $this->_testGetterAndSetterForProperty('country', (new Country));
        $this->_testGetterAndSetterForProperty('latitude', 47.1234);
        $this->_testGetterAndSetterForProperty('longitude', 5.1234);
    }

    /**
     * Test if getFullAddress() returns the full address.
     *
     * @test
     */
    public function getFullAddressReturnsFullAddress()
    {
        $this->subject->setName('name');
        $this->subject->setAddress('address');
        $this->subject->setZip('zip');
        $this->subject->setCity('city');
        $this->assertEquals('name, address, zip city', $this->subject->getFullAddress());
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new Location;
    }
}
