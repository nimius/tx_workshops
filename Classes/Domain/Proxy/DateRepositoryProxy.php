<?php
namespace NIMIUS\Workshops\Domain\Proxy;

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

use NIMIUS\Workshops\Domain\Model\Workshop;

/**
 * Date repository proxy class.
 *
 * Proxy object for filtering workshop dates in repositories.
 */
class DateRepositoryProxy
{
    /**
     * @var integer Storage page id
     */
    protected $pid;

    /**
     * @var integer Restrict dates to be within the following amount of days from now
     */
    protected $withinDaysFromNow;

    /**
     * @var bool Hide dates being in the past, regardless of time.
     */
    protected $hidePastDates;

    /**
     * @var bool Hide dates where workshops already started regarding time.
     */
    protected $hideAlreadyStartedDates;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Workshop Workshop to filter dates for.
     */
    protected $workshop;


    /**
     * Initialize proxy properties by given settings.
     *
     * Settings are coming from e.g. TypoScript or FlexForm.
     *
     * @param array $settings
     * @return void
     */
    public function initializeFromSettings(array $settings)
    {
        if ((int)$settings['upcomingDays'] > 0) {
            $this->setWithinDaysFromNow((int)$settings['upcomingDays']);
        }
        if ((bool)$settings['hidePastDates']) {
            $this->setHidePastDates(TRUE);
        }
        if ((bool)$settings['hideAlreadyStartedDates']) {
            $this->setHideAlreadyStartedDates(TRUE);
        }
    }

    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param integer $pid
     * @return void
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return mixed
     */
    public function getWithinDaysFromNow()
    {
        return $this->withinDaysFromNow;
    }

    /**
     * @param integer $withinDaysFromNow
     * @return void
     */
    public function setWithinDaysFromNow($withinDaysFromNow)
    {
        $this->withinDaysFromNow = $withinDaysFromNow;
    }

    /**
     * @return bool
     */
    public function getHidePastDates()
    {
        return $this->hidePastDates;
    }

    /**
     * @param bool $hidePastDates
     * @return void
     */
    public function setHidePastDates($hidePastDates)
    {
        $this->hidePastDates = $hidePastDates;
    }

    /**
     * @return bool
     */
    public function getHideAlreadyStartedDates()
    {
        return $this->hideAlreadyStartedDates;
    }

    /**
     * @param bool $hideAlreadyStartedDates
     * @return void
     */
    public function setHideAlreadyStartedDates($hideAlreadyStartedDates)
    {
        $this->hideAlreadyStartedDates = $hideAlreadyStartedDates;
    }

    /**
     * @return NULL|\NIMIUS\Workshops\Domain\Model\Workshop
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * @param NULL|\NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @return void
     */
    public function setWorkshop($workshop)
    {
        $this->workshop = $workshop;
    }
}