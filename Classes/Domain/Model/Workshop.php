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

use NIMIUS\Workshops\Domain\Model\Category;
use NIMIUS\Workshops\Domain\Model\Date;
use NIMIUS\Workshops\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Workshop model.
 */
class Workshop extends AbstractEntity
{

    /**
     * @var integer Default workshop
     */
    const TYPE_DEFAULT = 0;

    /**
     * @var integer Workshop with link to external page
     */
    const TYPE_EXTERNAL = 2;

    /**
     * @var boolean
     */
    protected $hidden;
    
    /**
     * @var integer
     */
    protected $type;

    /**
     * @var string
     */
    protected $identifier;
    
    /**
     * @var string
     */
    protected $internalUrl;
    
    /**
     * @var string
     */
    protected $externalUrl;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $abstract;

    /**
     * @var string
     */
    protected $description;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Date>
     * @lazy
     */
    protected $dates;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Category>
     * @lazy
     */
    protected $categories;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Workshop>
     * @lazy
     */
    protected $relatedWorkshops;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $images;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $files;



    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->categories = new ObjectStorage;
        $this->dates = new ObjectStorage;
        $this->relatedWorkshops = new ObjectStorage;
        $this->images = new ObjectStorage;
        $this->files = new ObjectStorage;
    }
    
    /**
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param boolean $hidden
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
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
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->type == self::TYPE_DEFAULT;
    }

    /**
     * @return boolean
     */
    public function getIsExternal()
    {
        return $this->type == self::TYPE_EXTERNAL;
    }
    
    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return void
     */
    public function setIdentifier($identifier)
    {
        return $this->identifier = $identifier;
    }
    
    /**
     * @return string
     */
    public function getInternalUrl()
    {
        return $this->internalUrl;
    }

    /**
     * @param string $url
     * @return void
     */
    public function setInternalUrl($url)
    {
        $this->internalUrl = $url;
    }
    
    /**
     * @return string
     */
    public function getExternalUrl()
    {
        return $this->externalUrl;
    }

    /**
     * @param string $url
     * @return void
     */
    public function setExternalUrl($url)
    {
        $this->externalUrl = $url;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param string $abstract
     * @return void
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Date>
     */
    public function getDates()
    {
        return $this->dates;
    }
    
    /**
     * Adds a date.
     *
     * @param $date \NIMIUS\Workshops\Domain\Model\Date
     */
    public function addDate(Date $date)
    {
        $this->dates->attach($date);
    }
    
    /**
     * Get all categories.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Category>
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    /**
     * Adds a category.
     *
     * @param $category \NIMIUS\Workshops\Domain\Model\Category
     */
    public function addCategory(Category $category)
    {
        $this->categories->attach($category);
    }
    
    /**
     * Get all related workshops.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NIMIUS\Workshops\Domain\Model\Workshop>
     */
    public function getRelatedWorkshops()
    {
        return $this->relatedWorkshops;
    }

    /**
     * Get all images.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Get all files.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Get the first category.
     *
     * @return \NIMIUS\Workshops\Domain\Model\Category
     */
    public function getFirstCategory()
    {
        $categories = $this->getCategories();
        if (!is_null($categories)) {
            $categories->rewind();
            return $categories->current();
        } else {
            return NULL;
        }
    }

}