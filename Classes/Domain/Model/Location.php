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

use NIMIUS\Workshops\DomainObject\AbstractEntity;
use SJBR\StaticInfoTables\Domain\Model\Country;

/**
 * Location model.
 */
class Location extends AbstractEntity
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var \SJBR\StaticInfoTables\Domain\Model\Country
     * @lazy
     */
    protected $country;

    /**
     * @var float
     */
    protected $latitude = 0.000000;

    /**
     * @var float
     */
    protected $longitude = 0.000000;


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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        $values = [
            $this->name, $this->address,
            trim("{$this->zip} {$this->city}")
        ];
        return implode(', ', $values);
    }
    
    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }
    
    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return \SJBR\StaticInfoTables\Domain\Model\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param \SJBR\StaticInfoTables\Domain\Model\Country|null $country
     * @return void
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;
    }

    /**
     * @return float|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }
    
    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

}