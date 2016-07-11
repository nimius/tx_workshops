<?php
namespace NIMIUS\Workshops\Domain\Model;

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

/**
 * Date model.
 *
 * @todo field "takes place" yes/no
 * @todo change "takes place" if minimum attendance reached
 */
class Date extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var integer Single date entry (One day)
     */
    const TYPE_SINGLE = 0;

    /**
     * @var integer Multiple date entries (Multiple days)
     */
    const TYPE_MULTIPLE = 1;

    /**
     * @var string  Event has ended already
     */
    const STATE_ENDED = 'ended';

    /**
     * @var string  Event is currently being held
     */
    const STATE_RUNNING = 'running';

    /**
     * @var string  Event is upcoming
     */
    const STATE_UPCOMING = 'upcoming';

    /**
     * @var integer
     */
    const PAYMENT_TYPE_FREE = 0;

    /**
     * @var integer
     */
    const PAYMENT_TYPE_PREPAY = 1;

    /**
     * @var integer
     */
    const PAYMENT_TYPE_BOX_OFFICE = 2;

    /**
     * @var integer
     */
    const PAYMENT_TYPE_EXTERNAL = 3;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Workshop
     */
    protected $workshop;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Date
     * @lazy
     */
    protected $parent;
    
    /**
     * @var \NIMIUS\Workshops\Domain\Model\Location
     */
    protected $location;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Instructor
     */
    protected $instructor;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Registration>
     * @lazy
     */
    protected $registrations;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Date>
     * @lazy
     */
    protected $dates;

    /**
     * @var integer
     */
    protected $type;

    /**
     * @var integer
     */
    protected $beginAt;
    
    /**
     * @var integer
     */
    protected $endAt;
    
    /**
     * @var boolean
     */
    protected $minimumAttendanceEnabled;
    
    /**
     * @var integer
     */
    protected $minimumAttendance;
    
    /**
     * @var boolean
     */
    protected $maximumAttendanceEnabled;
    
    /**
     * @var integer
     */
    protected $maximumAttendance;
    
    /**
     * @var integer
     */
    protected $registrationDeadlineAt;

    /**
     * @var string
     */
    protected $notes;

    /**
     * @var integer
     */
    protected $updatedAt;

    /**
     * @var integer
     */
    protected $paymentType;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $externalPaymentUrl;


    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
        $this->dates = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
    }
    
    /**
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \NIMIUS\Workshops\Domain\Model\Date
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param \NIMIUS\Workshops\Domain\Model\Date $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \NIMIUS\Workshops\Domain\Model\Workshop
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * @param \NIMIUS\Workshops\Domain\Model\Workshop $workshop
     */
    public function setWorkshop(\NIMIUS\Workshops\Domain\Model\Workshop $workshop)
    {
        $this->workshop = $workshop;
    }
    
    /**
     * @return \NIMIUS\Workshops\Domain\Model\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return \NIMIUS\Workshops\Domain\Model\Instructor
     */
    public function getInstructor()
    {
        return $this->instructor;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Registration>
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Date>
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $dates
     */
    public function setDates(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $dates)
    {
        $this->dates = $dates;
    }

    /**
     * @param \NIMIUS\Workshops\Domain\Model\Date $date
     */
    public function addDate(\NIMIUS\Workshops\Domain\Model\Date $date)
    {
        $this->dates->attach($date);
    }
    
    /**
     * @param \NIMIUS\Workshops\Domain\Model\Registration
     */
    public function addRegistration(\NIMIUS\Workshops\Domain\Model\Registration $registration)
    {
        $this->registrations->attach($registration);
    }
    
    /**
     * @return integer
     */
    public function getBeginAt()
    {
        return $this->beginAt;
    }

    /**
     * @param integer $beginAt
     */
    public function setBeginAt($beginAt)
    {
        $this->beginAt = $beginAt;
    }
    
    /**
     * @return integer
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param integer $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return integer
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return bool
     */
    public function getEndsOnSameDay()
    {
        return (date('Ymd', $this->getBeginAt()) == date('Ymd', $this->getEndAt()));
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }
    
    /**
     * @return boolean
     */
    public function getMaximumAttendanceEnabled()
    {
        return $this->maximumAttendanceEnabled;
    }
    
    /**
     * @param boolean $value
     */
    public function setMaximumAttendanceEnabled($value)
    {
        $this->maximumAttendanceEnabled = $value;
    }
    
    /**
     * @return integer
     */
    public function getMaximumAttendance()
    {
        return $this->maximumAttendance;
    }
    
    /**
     * @param integer $value
     */
    public function setMaximumAttendance($value)
    {
        $this->maximumAttendance = $value;
    }
    
    /**
     * @return mixed An integer if seat restrictions are in place, TRUE otherwise
     */
    public function getSeatsAvailable()
    {
        if (!$this->maximumAttendanceEnabled) {
            return TRUE;
        }

        $count = (int)($this->maximumAttendance - count($this->getRegistrations()));
        if ($count <= 0) {
            return 0;
        } else {
            return $count;
        }
    }
    
    /**
     * @return boolean
     */
    public function getMinimumAttendanceEnabled()
    {
        return $this->minimumAttendanceEnabled;
    }
    
    /**
     * @param boolean
     */
    public function setMinimumAttendanceEnabled($value)
    {
        $this->minimumAttendanceEnabled = $value;
    }
    
    /**
     * @return integer
     */
    public function getMinimumAttendance()
    {
        return $this->minimumAttendance;
    }
    
    /**
     * @param integer
     */
    public function setMinimumAttendance($value)
    {
        $this->minimumAttendance = $value;
    }
    
    /**
     * @return integer The amount of attendees / registrations required to meet the minimum required.
     */
    public function getAttendeesNeededForRequiredMinimum()
    {
        if (!$this->minimumAttendanceEnabled) {
            return 0;
        }
        
        $count = (int)($this->minimumAttendance - count($this->getRegistrations()));
        if ($count <= 0) {
            return 0;
        } else {
            return $count;
        }
    }
    
    /**
     * @return integer The amount of attendees / registrations still possible until the maximum is met.
     */
    public function getAttendeesNeededForPossibleMaximum()
    {
        if (!$this->maximumAttendanceEnabled) {
            return INF;
        }
        
        $count = (int)($this->maximumAttendance - count($this->getRegistrations()));
        if ($count <= 0) {
            return INF;
        } else {
            return $count;
        }
    }
    
    /**
     * @return integer
     */
    public function getRegistrationDeadlineAt()
    {
        return $this->registrationDeadlineAt;
    }
    
    /**
     * @param integer
     */
    public function setRegistrationDeadlineAt($value)
    {
        $this->registrationDeadlineAt = $value;
    }
    
    /**
     * @return bool
     */
    public function getRegistrationDeadlineReached()
    {
        if ((int)$this->registrationDeadlineAt == 0 || (int)$this->registrationDeadlineAt > time()) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * @return bool
     */
    public function getHasMultipleDates()
    {
        return $this->type == Date::TYPE_MULTIPLE;
    }

    /**
     * @return bool
     */
    public function getHasBegun()
    {
        return $this->beginAt < time();
    }

    /**
     * @return bool
     */
    public function getHasEnded()
    {
        return $this->endAt < time();
    }

    /**
     * Returns the state of this date. Returns one of the following state constants:
     * - STATE_ENDED
     * - STATE_RUNNING
     * - STATE_UPCOMING
     *
     * @return string
     */
    public function getState()
    {
        if ($this->getHasEnded()) {
            return self::STATE_ENDED;
        } elseif ($this->getHasBegun()) {
            return self::STATE_RUNNING;
        }
        return self::STATE_UPCOMING;
    }

    /**
     * Fake getter for dates, that sorts the dates
     * TODO find a way of solving the foreign sorting problem in TCA
     *
     * @return array
     */
    public function getDatesSorted()
    {
        $dates = $this->dates->toArray();
        usort($dates, function($a, $b) {
            return $a->getEndAt() - $b->getEndAt();
        });
        return $dates;
    }

    /**
     * @return integer
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @return bool
     */
    public function getPaymentTypeIsPrepay()
    {
        return ($this->paymentType == self::PAYMENT_TYPE_PREPAY);
        
    }

    /**
     * @return bool
     */
    public function getPaymentTypeIsBoxOffice()
    {
        return ($this->paymentType == self::PAYMENT_TYPE_BOX_OFFICE);
    }

    /**
     * @return bool
     */
    public function getPaymentTypeIsExternal()
    {
        return ($this->paymentType == self::PAYMENT_TYPE_EXTERNAL);
    }

    /**
     * @return float|NULL
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getExternalPaymentUrl()
    {
        return $this->externalPaymentUrl;
    }

}