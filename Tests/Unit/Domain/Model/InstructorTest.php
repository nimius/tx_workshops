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

use NIMIUS\Workshops\Domain\Model\Instructor;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Unit test case for Instructor model.
 */
class InstructorTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{
    /**
     * @var \NIMIUS\Workshops\Domain\Model\Instructor
     */
    protected $subject;

    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('name', 'Name test');
        $this->_testGetterAndSetterForProperty('abstract', 'Abstract test');
        $this->_testGetterAndSetterForProperty('email', 'Email test');
        $this->_testGetterAndSetterForProperty('profilePid', 6);
        $this->_testGetterAndSetterForProperty('images', (new ObjectStorage));
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new Instructor;
    }
}
