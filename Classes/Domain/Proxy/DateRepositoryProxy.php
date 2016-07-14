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
class DateRepositoryProxy extends AbstractRepositoryProxy
{

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Workshop Workshop to filter dates for.
     */
    protected $workshop;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Location Location to filter dates for.
     */
    protected $location;

    /**
     * @var integer
     */
    protected $recordLimit;

    /**
     * @var bool
     */
    protected $hideChildDates = TRUE;


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
        parent::initializeFromSettings($settings);
        if ((int)$settings['recordLimit'] > 0) {
            $this->setRecordLimit($settings['recordLimit']);
        }
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

    /**
     * @return NULL|\NIMIUS\Workshops\Domain\Model\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param NULL|\NIMIUS\Workshops\Domain\Model\Location $location
     * @return void
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getRecordLimit()
    {
        return $this->recordLimit;
    }

    /**
     * @param integer $recordLimit
     * @return void
     */
    public function setRecordLimit($recordLimit)
    {
        $this->recordLimit = (int)$recordLimit;
    }

    /**
     * @param bool $hide
     * @return void
     */
    public function setHideChildDates($hide)
    {
        $this->hideChildDates = $hide;
    }

    public function getHideChildDates()
    {
        return $this->hideChildDates;
    }

}