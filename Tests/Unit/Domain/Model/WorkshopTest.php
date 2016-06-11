<?php
namespace NIMIUS\Workshops\Test\Unit\Domain\Model;

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

class WorkshopTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var NIMIUS\Workshops\Domain\Model\Workshop
     */
    protected $subject;

    /**
     * @var TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;


    /**
     * Test if getFirstCategory() returns an ObjectStorage if no
     * category is assigned to a workshop.
     *
     * @test
     */
    public function getFirstCategoryReturnsObjectStorageWhenNoCategoryIsAssigned() {
        $this->assertEquals(new \TYPO3\CMS\Extbase\Persistence\ObjectStorage, $this->subject->getCategories());
    }
    
    /**
     * Test if getFirstCategory() returns the first category if
     * multiple categories are assigned to a workshop.
     *
     * @test
     */
    public function getFirstCategoryReturnsTheFirstCategoryWhenMultipleCategoriesAreAssigned() {
        $category1 = new \NIMIUS\Workshops\Domain\Model\Category;
        $this->subject->addCategory($category1);
        $category2 = new \NIMIUS\Workshops\Domain\Model\Category;
        $this->subject->addCategory($category2);
        
        $this->assertEquals($category1, $this->subject->getFirstCategory());
    }
    
    
    /**
     * Set up the test case.
     */
    protected function setUp() {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->subject = new \NIMIUS\Workshops\Domain\Model\Workshop;
    }

}