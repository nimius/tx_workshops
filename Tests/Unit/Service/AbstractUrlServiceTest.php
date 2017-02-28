<?php
namespace NIMIUS\Workshops\Test\Unit\Service;

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
require_once __DIR__ . '/../../AbstractUnitTestCase.php';

use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Service\AbstractUrlService;

/**
 * Unit test case for AbstractUrlService class.
 */
class AbstractUrlServiceTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{
    /**
     * @var \object Mock of AbstractUrlService.
     */
    protected $subject;

    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('object', []);
        $this->_testGetterAndSetterForProperty('object', (new Workshop));
        $this->_testGetterAndSetterForProperty('settings', ['foo' => 'bar']);
        $this->_testGetterAndSetterForProperty('settings', []);
        $this->_testGetterAndSetterForProperty('typolinkConfiguration', ['foo' => 'bar']);
        $this->_testGetterAndSetterForProperty('typolinkConfiguration', []);
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = $this->getMockForAbstractClass(AbstractUrlService::class);
    }
}
