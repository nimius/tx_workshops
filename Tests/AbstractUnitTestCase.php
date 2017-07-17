<?php
namespace NIMIUS\Workshops\Tests;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Abstract unit test case.
 *
 * Includes shared methods to aid in unit testing.
 */
abstract class AbstractUnitTestCase extends UnitTestCase
{
    /**
     * Helper to test a property getter and setter.
     *
     * The name is underscored to prevent phpunit calling it as a test itself.
     *
     * @param string $property
     * @param mixed $value
     * @return void
     */
    protected function _testGetterAndSetterForProperty($property, $value)
    {
        $setter = 'set' . ucfirst($property);
        $getter = 'get' . ucfirst($property);
        $this->subject->{$setter}($value);
        $this->assertEquals($value, $this->subject->{$getter}());
    }
}
