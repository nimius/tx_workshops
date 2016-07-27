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
 * Category model.
 */
class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{

    /**
     * @var integer
     */
    protected $txWorkshopsDetailPid;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $txWorkshopsImages;


    /**
     * @return integer
     */
    public function getWorkshopsDetailPid()
    {
        return $this->txWorkshopsDetailPid;
    }

    /**
     * @param integer $pid
     * @return void
     */
    public function setWorkshopsDetailPid($pid)
    {
        $this->txWorkshopsDetailPid = $pid;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getWorkshopsImages()
    {
        return $this->txWorkshopsImages;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage|null $images
     * @return void
     */
    public function setWorkshopsImages($images)
    {
        $this->txWorkshopsImages = $images;
    }

}