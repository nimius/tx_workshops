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

/**
 * Workshop repository proxy class.
 *
 * Proxy object for filtering workshops in repositories.
 */
class WorkshopRepositoryProxy extends AbstractRepositoryProxy
{

    /**
     * @var bool Hide workshops not having an upcoming date.
     */
    protected $hideWorkshopsWithoutUpcomingDates = false;

    /**
     * @var array|null Workshop types to filter for.
     */
    protected $types = [];


    /**
     * @return bool
     */
    public function getHideWorkshopsWithoutUpcomingDates()
    {
        return $this->hideWorkshopsWithoutUpcomingDates;
    }

    /**
     * @param bool $hide
     * @return void
     */
    public function setHideWorkshopsWithoutUpcomingDates($hide)
    {
        $this->hideWorkshopsWithoutUpcomingDates = $hide;
    }

    /**
     * @param array|null $types
     * @return void
     */
    public function setTypes($types)
    {
        $this->types = $types;
    }

    /**
     * @return array|null
     */
    public function getTypes()
    {
        return $this->types;
    }

}