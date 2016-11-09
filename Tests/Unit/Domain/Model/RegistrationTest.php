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

use NIMIUS\Workshops\Domain\Model\FrontendUser;
use NIMIUS\Workshops\Domain\Model\Language;
use NIMIUS\Workshops\Domain\Model\Registration;

/**
 * Unit test case for Registration model.
 */
class RegistrationTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Registration
     */
    protected $registration;

    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('company', 'Test company');
        $this->_testGetterAndSetterForProperty('firstName', 'Test first name');
        $this->_testGetterAndSetterForProperty('lastName', 'Test last name');
        $this->_testGetterAndSetterForProperty('address', 'Test address');
        $this->_testGetterAndSetterForProperty('zip', 'Test zip');
        $this->_testGetterAndSetterForProperty('city', 'Test city');
        $this->_testGetterAndSetterForProperty('country', 'Test country');
        $this->_testGetterAndSetterForProperty('email', 'test@example.com');
        $this->_testGetterAndSetterForProperty('telephone', '000 000 0000');
        $this->_testGetterAndSetterForProperty('notes', 'Test notes');
        $this->_testGetterAndSetterForProperty('frontendUser', (new FrontendUser));
        $this->_testGetterAndSetterForProperty('language', (new Language));
        $this->_testGetterAndSetterForProperty('additionalFieldsString', serialize(['foo' => 'bar']));
        $this->_testGetterAndSetterForProperty('additionalFields', ['foo' => 'bar']);
        $this->_testGetterAndSetterForProperty('confirmationSentAt', time());
        $this->_testGetterAndSetterForProperty('confirmationSentAt', null);
        $this->_testGetterAndSetterForProperty('createdAt', time());
        $this->_testGetterAndSetterForProperty('createdAt', null);
    }

    /**
     * Test if getFullName() returns the full name.
     *
     * @test
     */
    public function getFullNameReturnsFullName()
    {
        $this->subject->setFirstName('First name');
        $this->subject->setLastName('Last name');
        $this->assertEquals('First name Last name', $this->subject->getFullName());

        $this->subject->setLastName('');
        $this->assertEquals('First name', $this->subject->getFullName());
    }

    /**
     * Test if populateFromFrontendUser() sets fields available.
     *
     * @test
     */
    public function populateFromFrontendUserSetsAvailableFields()
    {
        $user = new FrontendUser;
        $user->setCompany('Company');
        $user->setFirstName('First name');
        $user->setLastName('Last name');
        $user->setAddress('Address');
        $user->setZip('Zip');
        $user->setCity('City');
        $user->setEmail('email@example.com');
        $this->subject->setFrontendUser($user);
        $this->subject->populateFromFrontendUser();
        $this->assertEquals('First name', $this->subject->getFirstName());
        $this->assertEquals('Last name', $this->subject->getLastName());
        $this->assertEquals('Address', $this->subject->getAddress());
        $this->assertEquals('Zip', $this->subject->getZip());
        $this->assertEquals('City', $this->subject->getCity());
        $this->assertEquals('Company', $this->subject->getCompany());
        $this->assertEquals('email@example.com', $this->subject->getEmail());
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new Registration;
    }
}
