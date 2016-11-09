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

use NIMIUS\Workshops\Domain\Model\Category;
use NIMIUS\Workshops\Domain\Model\Workshop;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Unit test case for Workshop model.
 */
class WorkshopTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Workshop
     */
    protected $subject;

    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('hidden', true);
        $this->_testGetterAndSetterForProperty('type', Workshop::TYPE_EXTERNAL);
        $this->_testGetterAndSetterForProperty('identifier', 'test-001');
        $this->_testGetterAndSetterForProperty('internalUrl', '23');
        $this->_testGetterAndSetterForProperty('externalUrl', 'http://example.com');
        $this->_testGetterAndSetterForProperty('name', 'Test Workshop');
        $this->_testGetterAndSetterForProperty('abstract', 'Test Workshop abstract');
        $this->_testGetterAndSetterForProperty('description', 'Workshop description');
        $this->_testGetterAndSetterForProperty('categories', (new ObjectStorage));
        $this->_testGetterAndSetterForProperty('relatedWorkshops', (new ObjectStorage));
        $this->_testGetterAndSetterForProperty('images', (new ObjectStorage));
        $this->_testGetterAndSetterForProperty('files', (new ObjectStorage));
    }

    /**
     * Test if getFirstCategory() returns an ObjectStorage if no
     * category is assigned to a workshop.
     *
     * @test
     */
    public function getFirstCategoryReturnsObjectStorageWhenNoCategoryIsAssigned()
    {
        $this->assertEquals(new ObjectStorage, $this->subject->getCategories());
    }

    /**
     * Test if addCategory() adds a category
     *
     * @test
     */
    public function addCategoryAddsACategory()
    {
        $categoriesCount = count($this->subject->getCategories());
        $this->subject->addCategory((new Category));
        $this->assertEquals(($categoriesCount + 1), count($this->subject->getCategories()));
    }

    /**
     * Test if getFirstCategory() returns the first category if
     * multiple categories are assigned to a workshop.
     *
     * @test
     */
    public function getFirstCategoryReturnsTheFirstCategoryWhenMultipleCategoriesAreAssigned()
    {
        $category1 = new Category;
        $this->subject->addCategory($category1);
        $category2 = new Category;
        $this->subject->addCategory($category2);
        $this->assertEquals($category1, $this->subject->getFirstCategory());
    }

    /**
     * Test if getIsDefault() returns true if type is set to default.
     *
     * @test
     */
    public function getIsDefaultReturnsTrueIfTypeIsSetToDefault()
    {
        $this->subject->setType(Workshop::TYPE_DEFAULT);
        $this->assertEquals(true, $this->subject->getIsDefault());
    }

    /**
     * Test if getIsExternal() returns true if type is set to external.
     *
     * @test
     */
    public function getIsExternalReturnsTrueIfTypeIsSetToExternal()
    {
        $this->subject->setType(Workshop::TYPE_EXTERNAL);
        $this->assertEquals(true, $this->subject->getIsExternal());
    }

    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new Workshop;
    }
}
