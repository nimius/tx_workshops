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

// Manually requiring custom class as it is not autoloaded in the bootstrap process.
require_once __DIR__ . '/../../../AbstractUnitTestCase.php';

use NIMIUS\Workshops\Domain\Model\Date;
use NIMIUS\Workshops\Domain\Model\Instructor;
use NIMIUS\Workshops\Domain\Model\Location;
use NIMIUS\Workshops\Domain\Model\Registration;
use NIMIUS\Workshops\Domain\Model\Workshop;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Unit test case for Date model.
 */
class DateTest extends \NIMIUS\Workshops\Tests\AbstractUnitTestCase
{

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Date
     */
    protected $subject;


    /**
     * Test getter/setter for properties.
     *
     * @test
     */
    public function testSettersAndGettersForProperties()
    {
        $this->_testGetterAndSetterForProperty('type', Date::TYPE_MULTIPLE);
        $this->_testGetterAndSetterForProperty('parent', (new Date));
        $this->_testGetterAndSetterForProperty('parent', null);
        $this->_testGetterAndSetterForProperty('workshop', (new Workshop));
        $this->_testGetterAndSetterForProperty('workshop', null);
        $this->_testGetterAndSetterForProperty('location', (new Location));
        $this->_testGetterAndSetterForProperty('location', null);
        $this->_testGetterAndSetterForProperty('instructor', (new Instructor));
        $this->_testGetterAndSetterForProperty('instructor', null);
        $this->_testGetterAndSetterForProperty('dates', (new ObjectStorage));
        $this->_testGetterAndSetterForProperty('beginAt', time());
        $this->_testGetterAndSetterForProperty('endAt', time());
        $this->_testGetterAndSetterForProperty('updatedAt', time());
        $this->_testGetterAndSetterForProperty('notes', 'Foo Bar');
        $this->_testGetterAndSetterForProperty('maximumAttendanceEnabled', true);
        $this->_testGetterAndSetterForProperty('maximumAttendanceEnabled', false);
        $this->_testGetterAndSetterForProperty('maximumAttendance', 0);
        $this->_testGetterAndSetterForProperty('maximumAttendance', 12);
        $this->_testGetterAndSetterForProperty('minimumAttendanceEnabled', true);
        $this->_testGetterAndSetterForProperty('minimumAttendanceEnabled', false);
        $this->_testGetterAndSetterForProperty('minimumAttendance', 0);
        $this->_testGetterAndSetterForProperty('minimumAttendance', 5);
        $this->_testGetterAndSetterForProperty('registrationDeadlineAt', time());
        $this->_testGetterAndSetterForProperty('paymentType', Date::PAYMENT_TYPE_BOX_OFFICE);
        $this->_testGetterAndSetterForProperty('price', 13.50);
        $this->_testGetterAndSetterForProperty('price', null);
        $this->_testGetterAndSetterForProperty('externalPaymentUrl', 'http://example.com');
    }

    /**
     * Test if getEndsOnSameDay() returns true if beginAt and endAt
     * are the same day.
     *
     * @test
     */
    public function getEndsOnSameDayReturnsTrueWhenTheDateMatches()
    {
        $this->subject->setBeginAt(time());
        $this->subject->setEndAt(time());
        $this->assertEquals(true, $this->subject->getEndsOnSameDay());
    }

    /**
     * Test if getSeatsAvailable() returns true when the feature is disabled.
     *
     * @test
     */
    public function getSeatsAvailableReturnsTrueIfFeatureIsDisabled()
    {
        $this->subject->setMaximumAttendanceEnabled(false);
        $this->assertEquals(true, $this->subject->getSeatsAvailable());
    }

    /**
     * Test if getSeatsAvailable() returns a correct integer when the feature is enabled.
     *
     * @test
     */
    public function getSeatsAvailableReturnsANumberOfSeatsIfEnabled()
    {
        $this->subject->setMaximumAttendanceEnabled(true);
        $this->subject->setMaximumAttendance(6);
        $this->assertEquals(6, $this->subject->getSeatsAvailable());

        $this->subject->addRegistration(new Registration);
        $this->assertEquals(5, $this->subject->getSeatsAvailable());
    }

    /**
     * Test if getSeatsAvailable() returns true if seat restriction
     * is not active for the given date.
     *
     * @test
     */
    public function getSeatsAvailableReturnsTrueWhenRestrictionIsDisabled()
    {
        $this->subject->setMaximumAttendanceEnabled(false);
        $this->assertEquals(true, $this->subject->getSeatsAvailable());
    }

    /**
     * Test if getAttendeesNeededForRequiredMinimum() returns 0 when the feature
     * is disabled.
     *
     * @test
     */
    public function getAttendeesNeededForRequiredMinimumReturnsZeroWhenFeatureIsDisabled()
    {
        $this->subject->setMinimumAttendanceEnabled(false);
        $this->assertEquals(0, $this->subject->getAttendeesNeededForRequiredMinimum());
    }

    /**
     * Test if getAttendeesNeededForRequiredMinimum() returns a correct integer when the feature
     * is enabled.
     *
     * @test
     */
    public function getAttendeesNeededForRequiredMinimumReturnsACorrectNumberWhenFeatureIsDisabled()
    {
        $this->subject->setMinimumAttendanceEnabled(true);
        $this->subject->setMinimumAttendance(5);
        $this->assertEquals(5, $this->subject->getAttendeesNeededForRequiredMinimum());

        $this->subject->addRegistration(new Registration);
        $this->assertEquals(4, $this->subject->getAttendeesNeededForRequiredMinimum());
    }

    /**
     * Test if getPaymentTypeIsBoxOffice() returns true if payment type
     * is set accordingly.
     *
     * @test
     */
    public function getPaymentTypeIsBoxOfficeReturnsTrueWhenAccordingPaymentTypeIsSet()
    {
        $this->subject->setPaymentType(Date::PAYMENT_TYPE_BOX_OFFICE);
        $this->assertEquals(true, $this->subject->getPaymentTypeIsBoxOffice());
    }

    /**
     * Test if getPaymentTypeIsBoxOffice() returns true if payment type
     * is set accordingly.
     *
     * @test
     */
    public function getPaymentTypeIsExternalReturnsTrueWhenAccordingPaymentTypeIsSet()
    {
        $this->subject->setPaymentType(Date::PAYMENT_TYPE_EXTERNAL);
        $this->assertEquals(true, $this->subject->getPaymentTypeIsExternal());
    }

    /**
     * Test if getPaymentTypeIsPrepay() returns true if payment type
     * is set accordingly.
     *
     * @test
     */
    public function getPaymentTypeIsPrepayReturnsTrueWhenAccordingPaymentTypeIsSet()
    {
        $this->subject->setPaymentType(Date::PAYMENT_TYPE_PREPAY);
        $this->assertEquals(true, $this->subject->getPaymentTypeIsPrepay());
    }

    /**
     * Test if getSeatsAvailable() returns a correct number if no 
     * registrations are present.
     *
     * @test
     */
    public function getSeatsAvailableReturnsACorrectNumberWhenNoRegistrationsArePresent()
    {
        $this->subject->setMaximumAttendanceEnabled(true);
        $this->subject->setMaximumAttendance(10);
        $this->assertEquals(10, $this->subject->getSeatsAvailable());
    }

    /**
     * Test if getSeatsAvailable() returns a correct number if any 
     * registrations are present.
     *
     * @test
     */
    public function getSeatsAvailableReturnsACorrectIntegerWhenAnyRegistrationsArePresent()
    {
        $this->subject->setMaximumAttendanceEnabled(true);
        $this->subject->setMaximumAttendance(10);
        $this->subject->addRegistration((new Registration));
        $this->assertEquals(9, $this->subject->getSeatsAvailable());
    }

    /**
     * Test if getAttendeesNeededForRequiredMinimum() returns a correct number if any 
     * registrations are present.
     *
     * @test
     */
    public function getAttendeesNeededForRequiredMinimumReturnsACorrectIntegerWhenAnyRegistrationsArePresent()
    {
        $this->subject->setMinimumAttendanceEnabled(true);
        $this->subject->setMinimumAttendance(4);
        $this->subject->addRegistration((new Registration));
        $this->assertEquals(3, $this->subject->getAttendeesNeededForRequiredMinimum());
    }

    /**
     * Test if getAttendeesNeededForRequiredMinimum() returns zero if
     * the feature is disabled.
     *
     * @test
     */
    public function getAttendeesNeededForRequiredMinimumReturnsZeroWhenTheFeatureIsDisabled()
    {
        $this->subject->setMinimumAttendanceEnabled(false);
        $this->subject->setMinimumAttendance(4);
        $this->subject->addRegistration((new Registration));
        $this->assertEquals(0, $this->subject->getAttendeesNeededForRequiredMinimum());
    }

    /**
     * Test if getAttendeesNeededForPossibleMaximum() returns a correct number if any 
     * registrations are present.
     *
     * @test
     */
    public function getAttendeesNeededForPossibleMaximumReturnsACorrectIntegerWhenAnyRegistrationsArePresent()
    {
        $this->subject->setMaximumAttendanceEnabled(true);
        $this->subject->setMaximumAttendance(4);
        $this->subject->addRegistration((new Registration));

        $this->assertEquals(3, $this->subject->getAttendeesNeededForPossibleMaximum());
    }

    /**
     * Test if getAttendeesNeededForPossibleMaximum() returns infinite if
     * the feature is disabled.
     *
     * @test
     */
    public function getAttendeesNeededForPossibleMaximumReturnsZeroWhenTheFeatureIsDisabled()
    {
        $this->subject->setMaximumAttendanceEnabled(false);
        $this->subject->setMaximumAttendance(4);
        $this->subject->addRegistration((new Registration));

        $this->assertEquals(INF, $this->subject->getAttendeesNeededForPossibleMaximum());
    }

    /**
     * Test if getRegistrationDeadlineReached returns false if the deadline
     * Test if getRegistrationDeadlineReached returns false if the deadline
     * is set to '0'.
     *
     * @test
     */
    public function getRegistrationDeadlineReachedReturnsFalseWhenSetToZero()
    {
        $this->subject->setRegistrationDeadlineAt(0);
        $this->assertEquals(false, $this->subject->getRegistrationDeadlineReached());
    }

    /**
     * Test if getRegistrationDeadlineReached returns false if the deadline
     * is set to a date/time newer than now.
     *
     * @test
     */
    public function getRegistrationDeadlineReachedReturnsFalseWhenSetToNewerThanNow()
    {
        $this->subject->setRegistrationDeadlineAt(time() + 60);
        $this->assertEquals(false, $this->subject->getRegistrationDeadlineReached());
    }

    /**
     * Test if getRegistrationDeadlineReached returns true if the deadline
     * is set to a date/time older than now.
     *
     * @test
     */
    public function getRegistrationDeadlineReachedReturnsTrueWhenSetToOlderThanNow()
    {
        $this->subject->setRegistrationDeadlineAt(time() - 60);
        $this->assertEquals(true, $this->subject->getRegistrationDeadlineReached());
    }

    /**
     * Test if getHasMultipleDates() returns true if type is set to multiple.
     *
     * @test
     */
    public function getHasMultipleDatesReturnsTrueTypeMultipleIsSet()
    {
        $this->subject->setType(Date::TYPE_MULTIPLE);
        $this->assertEquals(true, $this->subject->getHasMultipleDates());
    }

    /**
     * Test if getHasBegun() returns true if the current time is newer than
     * the date's beginAt.
     *
     * @test
     */
    public function getHasBegunReturnsTrueIfDateHasBegun()
    {
        $this->subject->setBeginAt(time() - 200);
        $this->assertEquals(true, $this->subject->getHasBegun());
    }

    /**
     * Test if getHasEnded() returns true if the current time is older than
     * the date's endAt.
     *
     * @test
     */
    public function getHasEndedReturnsTrueIfDateHasBegun()
    {
        $this->subject->setEndAt(time() - 200);
        $this->assertEquals(true, $this->subject->getHasEnded());
    }


    /**
     * Set up the test case.
     */
    protected function setUp()
    {
        $this->subject = new Date;
    }

}