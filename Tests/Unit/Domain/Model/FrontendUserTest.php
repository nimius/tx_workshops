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

/**
 * Unit test case for FrontendUser model.
 */
class FrontendUserTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{

    /**
     * @var \NIMIUS\Workshops\Domain\Model\FrontendUser
     */
    protected $subject;

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
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new FrontendUser;
    }
}
