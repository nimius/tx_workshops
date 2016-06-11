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
 * The registration model.
 *
 * This is basically the join model between attendee and workshop date.
 */
class Registration extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var \NIMIUS\Workshops\Domain\Model\FrontendUser
     * @lazy
     */
    protected $frontendUser;
    
    /**
     * @var \NIMIUS\Workshops\Domain\Model\Date
     * @lazy
     */
    protected $workshopDate;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Language
     * @lazy
     */
    protected $language;
    
    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

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
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $telephone;

    /**
     * @var string
     */
    protected $notes;

    /**
     * @var string Serialized version of additionalFields
     */
    protected $additionalFieldsString = 'a:0:{}';

    /**
     * @var array
     */
    protected $additionalFields = [];
    
    /**
     * @var integer
     */
    protected $confirmationSentAt;
    
    /**
     * @var integer
     */
    protected $crdate;
    
    
    
    /**
     * Populate this object's properties with values from the
     * assigned frontend user's properties.
     *
     * @return void
     */
    public function populateFromFrontendUser()
    {
        if (!$this->getFrontendUser()) {
            return;
        }
        $properties = $this->getFrontendUser()->_getProperties();
        unset($properties['uid'], $properties['pid'], $properties['password']);
        foreach($properties as $property => $value) {
            $this->_setProperty($property, $value);
        }
    }
    
    /**
     * @return string
     */
    public function getFullName()
    {
        $parts = [
            $this->firstName,
            $this->lastName
        ];
        return implode(' ', array_filter($parts, 'strlen'));
    }
    
    
    /**
     * @return \NIMIUS\Workshops\Domain\Model\FrontendUser
     */
    public function getFrontendUser()
    {
        return $this->frontendUser;
    }
    
    /**
     * @var \NIMIUS\Workshops\Domain\Model\FrontendUser $frontendUser
     */
    public function setFrontendUser(FrontendUser $frontendUser)
    {
        $this->frontendUser = $frontendUser;
    }
    
    /**
     * @return \NIMIUS\Workshops\Domain\Model\Date
     */
    public function getWorkshopDate()
    {
        return $this->workshopDate;
    }
    
    /**
     * @var \NIMIUS\Workshops\Domain\Model\Date $date
     */
    public function setWorkshopDate(Date $date)
    {
        $this->workshopDate = $date;
    }

    /**
     * @return \NIMIUS\Workshops\Domain\Model\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * @var \NIMIUS\Workshops\Domain\Model\Language $language
     */
    public function setLanguage(Language $language)
    {
        $this->language = $language;
    }
    
    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return string
     */
    public function getAdditionalFieldsString()
    {
        return $this->additionalFieldsString;
    }

    /**
     * @param string $string
     */
    public function setAdditionalFieldsString($string)
    {
        $this->additionalFieldsString = $string;
    }

    /**
     * @return array
     */
    public function getAdditionalFields()
    {
        return unserialize($this->getAdditionalFieldsString());
    }

    /**
     * @param array $fields
     */
    public function setAdditionalFields($fields)
    {
        $this->setAdditionalFieldsString(serialize($fields));
    }
    
    /**
     * @param integer
     */
    public function setConfirmationSentAt($value)
    {
        $this->confirmationSentAt = $value;
    }
    
    /**
     * @return integer
     */
    public function getConfirmationSentAt()
    {
        return $this->confirmationSentAt;
    }
    
    /**
     * @return integer
     */
    public function getCreatedAt()
    {
        return $this->crdate;

    }

}