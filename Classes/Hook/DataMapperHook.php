<?php
namespace NIMIUS\Workshops\Hook;

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

use NIMIUS\Workshops\Domain\Model\Date;
use NIMIUS\Workshops\Utility\ObjectUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * DataMapper hook class.
 * 
 * Contains functionality to hook into backend data processing.
 */
class DataMapperHook
{

    /**
     * Hook to post-process data. 
     *
     * @param string $status
     * @param string $table
     * @param integer $id
     * @param array &$fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler
     * @return void
     */
    public function processDatamap_postProcessFieldArray($status, $table, $uid, &$fieldArray, &$dataHandler)
    {
        $dateDataMap = $dataHandler->datamap['tx_workshops_domain_model_date'];
        if ($table === 'tx_workshops_domain_model_date' && $dateDataMap) {
            $this->processDateRecords($status, $table, $uid, $fieldArray, $dataHandler, $dateDataMap);
        }
    }
    
    /**
     * Hook to update data after all database operations.
     *
     * @param string $status
     * @param string $table
     * @param integer $id
     * @param array &$fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler
     * @return void
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $uid, &$fieldArray, &$dataHandler)
    {
        if ($table == 'tx_workshops_domain_model_location') {
            $this->geocodeRecord($status, $table, $uid, $fieldArray, $dataHandler);
        }
    }

    /**
     * Get latitude/longitude from given data and update record.
     *
     * Updates the database entry by adding the correct geocoded values. If the extension 'geocoding'
     * by Benjamin Mack is not installed, this function does nothing.
     *
     * @param string $status
     * @param string $table
     * @param integer $id
     * @param array &$fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler
     * @return void
     */
    protected function geocodeRecord($status, $table, $uid, &$fieldArray, &$dataHandler)
    {
        $logManager = GeneralUtility::makeInstance(LogManager::class);
        if (!ExtensionManagementUtility::isLoaded('geocoding'))  {
            /** @var LogManager $logManager */
            $logManager->getLogger(__CLASS__)->warning('EXT:geocoding is not installed');
            return;
        }

        // if the record is new, it has no uid yet (uid is set to "NEW12345")
        if (!is_int($uid)) {
            $uid = $dataHandler->substNEWwithIDs[$uid];
        }

        // fetching the important information from the database
        $record = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('name, address, zip, city, country', $table, 'uid = ' . (int)$uid);
        if (count($record) === 0) {
            $logManager->getLogger(__CLASS__)->warning('Cannot geocode: record ' . $uid . ' does not exist in table ' . $table);
            error_log('Cannot geocode: record ' . $uid . ' does not exist in table ' . $table);
            return;
        }
        $record = $record[0];


        // getting the name of the country
        $countryRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('cn_official_name_en', 'static_countries', 'uid = ' . (int)$record['country']);
        if (count($countryRecords) === 0) {
            $logManager->getLogger(__CLASS__)->warning('Cannot geocode: country ' . (int)$record['country'] . ' does not exist');
            error_log('Cannot geocode: country ' . (int)$record['country'] . ' does not exist');
            return;
        }
        $country = $countryRecords[0]['cn_official_name_en'];

        // using a string here in case the class is not defined. (if the extension is not installed).
        // trying to initialize a non-existing class with objectManager will return null, but using Foo:class
        // if the class is non-existent will yield a PHP fatal error
        $geoService = ObjectUtility::getObjectManager()->get('B13\\Geocoding\\Service\\GeoService');
        $street = $record['address'];
        if (!empty($record['name'])) {
            $street = $record['name'] . ', ' . $street;
        }
        $coordinates = $geoService->getCoordinatesForAddress(
            $street,
            $record['zip'],
            $record['city'],
            $country
        );

        // setting the coordinates
        if (array_key_exists('latitude', $coordinates) && array_key_exists('longitude', $coordinates)) {
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
                $table,
                'uid = ' . (int)$uid,
                $coordinates
            );
        }
    }

    /**
     * Process date record available in given data.
     *
     * Stores earliest begin_at and latest end_at of child records in the current record if it is
     * of type Date::TYPE_MULTIPLE to simplify repository and view logics.
     *
     * @param string $status
     * @param string $table
     * @param integer $id
     * @param array &$fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler
     * @return void
     */
    protected function processDateRecords($status, $table, $uid, &$fieldArray, &$dataHandler, $dateDataMap)
    {
        if (!is_numeric($uid)) {
            $uid = $dataHandler->substNEWwithIDs[$uid];
        }

        $properties = $dateDataMap[$uid];
        if ((int)$properties['type'] !== Date::TYPE_MULTIPLE) {
            return;
        }
        $dates = explode(',', $properties['dates']);

        // In order for dates to be updated accurately without being influenced by past dates
        // they need to be reset to sensible defaults that fulfill the 2 following criteria:
        // 1. is always larger / smaller than any possible real date
        // 2. if there is no child, the record must not be found by the repository
        $properties['begin_at'] = PHP_INT_MAX;
        $properties['end_at'] = 0;

        foreach ($dates as $date) {
            if (!is_numeric($date)) {
                // This is a new record without a saved children to fetch from the DB, but
                // available in $dateDataMap with the same array key as the "dates" value.
                if (!array_key_exists($date, $dateDataMap)) {
                    continue;
                }
                $record = $dateDataMap[$date];
                $this->updateDateDatamap($record, $properties);
            } else {
                $record = BackendUtility::getRecord('tx_workshops_domain_model_date', $date);
                $this->updateDateDatamap($record, $properties);
            }
        }
        
        $fieldArray['begin_at'] = $properties['begin_at'];
        $fieldArray['end_at'] = $properties['end_at'];
    }

    /**
     * Updates the datamap with data calculated from the given record.
     *
     * @param array $record
     * @param array &$datamap
     * @return void
     */
    protected function updateDateDatamap($record, &$datamap)
    {
        if ($record['begin_at'] < $datamap['begin_at'] || (int)$datamap['begin_at'] == 0) {
            $datamap['begin_at'] = $record['begin_at'];
        }
        if ($record['end_at'] > $datamap['end_at'] || (int)$datamap['end_at'] == 0) {
            $datamap['end_at'] = $record['end_at'];
        }
    }

}