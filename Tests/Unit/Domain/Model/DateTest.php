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

class DateTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var NIMIUS\Workshops\Domain\Model\Date
     */
    protected $subject;

    /**
     * @var TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;


    /**
     * Test if getSeatsAvailable() returns TRUE if seat restriction
     * is not active for the given date.
     *
     * @test
     */
    public function getSeatsAvailableReturnsTrueWhenRestrictionIsDisabled() {
        $this->subject->setMaximumAttendanceEnabled(FALSE);
        $this->assertEquals(TRUE, $this->subject->getSeatsAvailable());
    }
    
    /**
     * Test if getSeatsAvailable() returns a correct number if no 
     * registrations are present.
     *
     * @test
     */
    public function getSeatsAvailableReturnsACorrectIntegerWhenNoRegistrationsArePresent() {
        $this->subject->setMaximumAttendanceEnabled(TRUE);
        $this->subject->setMaximumAttendance(10);
        $this->assertEquals(10, $this->subject->getSeatsAvailable());
    }
    
    /**
     * Test if getSeatsAvailable() returns a correct number if any 
     * registrations are present.
     *
     * @test
     */
    public function getSeatsAvailableReturnsACorrectIntegerWhenAnyRegistrationsArePresent() {
        $this->subject->setMaximumAttendanceEnabled(TRUE);
        $this->subject->setMaximumAttendance(10);
        
        $registration = new \NIMIUS\Workshops\Domain\Model\Registration;
        $this->subject->addRegistration($registration);
        
        $this->assertEquals(9, $this->subject->getSeatsAvailable());
    }
    
    /**
     * Test if getAttendeesNeededForRequiredMinimum() returns a correct number if any 
     * registrations are present.
     *
     * @test
     */
    public function getAttendeesNeededForRequiredMinimumReturnsACorrectIntegerWhenAnyRegistrationsArePresent() {
        $this->subject->setMinimumAttendanceEnabled(TRUE);
        $this->subject->setMinimumAttendance(4);
        
        $registration = new \NIMIUS\Workshops\Domain\Model\Registration;
        $this->subject->addRegistration($registration);
        
        $this->assertEquals(3, $this->subject->getAttendeesNeededForRequiredMinimum());
    }
    
    /**
     * Test if getAttendeesNeededForRequiredMinimum() returns zero if
     * the feature is disabled.
     *
     * @test
     */
    public function getAttendeesNeededForRequiredMinimumReturnsZeroWhenTheFeatureIsDisabled() {
        $this->subject->setMinimumAttendanceEnabled(FALSE);
        $this->subject->setMinimumAttendance(4); // Dummy value
        
        $registration = new \NIMIUS\Workshops\Domain\Model\Registration;
        $this->subject->addRegistration($registration);
        
        $this->assertEquals(0, $this->subject->getAttendeesNeededForRequiredMinimum());
    }
    
    /**
     * Test if getAttendeesNeededForPossibleMaximum() returns a correct number if any 
     * registrations are present.
     *
     * @test
     */
    public function getAttendeesNeededForPossibleMaximumReturnsACorrectIntegerWhenAnyRegistrationsArePresent() {
        $this->subject->setMaximumAttendanceEnabled(TRUE);
        $this->subject->setMaximumAttendance(4);
        
        $registration = new \NIMIUS\Workshops\Domain\Model\Registration;
        $this->subject->addRegistration($registration);
        
        $this->assertEquals(3, $this->subject->getAttendeesNeededForPossibleMaximum());
    }
    
    /**
     * Test if getAttendeesNeededForPossibleMaximum() returns infinite if
     * the feature is disabled.
     *
     * @test
     */
    public function getAttendeesNeededForPossibleMaximumReturnsZeroWhenTheFeatureIsDisabled() {
        $this->subject->setMaximumAttendanceEnabled(FALSE);
        $this->subject->setMaximumAttendance(4); // Dummy value
        
        $registration = new \NIMIUS\Workshops\Domain\Model\Registration;
        $this->subject->addRegistration($registration);
        
        $this->assertEquals(INF, $this->subject->getAttendeesNeededForPossibleMaximum());
    }
    
    /**
     * Test if getRegistrationDeadlineReached returns FALSE if the deadline
     * is set to '0'.
     *
     * @test
     */
    public function getRegistrationDeadlineReachedReturnsFalseWhenSetToZero() {
        $this->subject->setRegistrationDeadlineAt(0);
        
        $this->assertEquals(FALSE, $this->subject->getRegistrationDeadlineReached());
    }
    
    /**
     * Test if getRegistrationDeadlineReached returns FALSE if the deadline
     * is set to a date/time newer than now.
     *
     * @test
     */
    public function getRegistrationDeadlineReachedReturnsFalseWhenSetToNewerThanNow() {
        $this->subject->setRegistrationDeadlineAt(time() + 60);
        
        $this->assertEquals(FALSE, $this->subject->getRegistrationDeadlineReached());
    }
    
    /**
     * Test if getRegistrationDeadlineReached returns TRUE if the deadline
     * is set to a date/time older than now.
     *
     * @test
     */
    public function getRegistrationDeadlineReachedReturnsTrueWhenSetToOlderThanNow() {
        $this->subject->setRegistrationDeadlineAt(time() - 60);
        
        $this->assertEquals(TRUE, $this->subject->getRegistrationDeadlineReached());
    }
    

    /**
     * Set up the test case.
     */
    protected function setUp() {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->subject = new \NIMIUS\Workshops\Domain\Model\Date;
    }

}