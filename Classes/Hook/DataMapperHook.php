<?php
namespace NIMIUS\Workshops\Hook;

/*
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
use NIMIUS\Workshops\Utility\ConfigurationUtility;
use NIMIUS\Workshops\Utility\ObjectUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
     * @param int $id
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
     * @param int $id
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
     * Updates the database entry by adding latitude/longitude values.
     *
     * @param string $status
     * @param string $table
     * @param int $id
     * @param array &$fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler
     * @return void
     */
    protected function geocodeRecord($status, $table, $uid, &$fieldArray, &$dataHandler)
    {
        if (!ExtensionManagementUtility::isLoaded('geocoding')) {
            return;
        }
        // geocoding should only be executed, if the user a) updated the address and b) the coordinates were not manually changed by the user.
        if (!$this->isAddressUpdate($fieldArray) || $this->isManualCoordinateUpdate($fieldArray)) {
            return;
        }

        if (!is_int($uid)) {
            $uid = $dataHandler->substNEWwithIDs[$uid];
        }

        $record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
            '*',
            $table,
            'uid = ' . (int)$uid
        );
        if (!$record) {
            return;
        }

        $extensionConfiguation = ConfigurationUtility::getExtensionConfiguration();
        if (
            (float)$record['latitude'] != 0.0
            && (float)$record['longitude'] != 0.0
            && !(bool)$extensionConfiguation['locations.']['alwaysGeocode']
        ) {
            exit;
            // Only geocode address if either latitude/longitude is missing, or alwaysGeocode is set to enforce value updates.
            return;
        }

        $countryRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
            'cn_short_en',
            'static_countries',
            'uid = ' . (int)$record['country']
        );
        if (!$countryRecord) {
            return;
        }

        // Using a fully qualified class name as string to instantiate here in case the class is not defined.
        $geoService = ObjectUtility::getObjectManager()->get('B13\\Geocoding\\Service\\GeoService');
        $address = $record['address'];
        if (!empty($record['name'])) {
            $address = $record['name'] . ', ' . $address;
        }
        $coordinates = $geoService->getCoordinatesForAddress(
            $address,
            $record['zip'],
            $record['city'],
            $countryRecord['cn_short_en']
        );

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
     * @param int $id
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

    /**
     * Returns true if the given fieldArray of a tx_workshops_domain_model_location record contains address updates.
     * Geocoding is only necessary, if the address has been updated
     *
     * @param array $fieldArr
     * @return bool
     */
    protected function isAddressUpdate(&$fieldArr)
    {
        return  array_key_exists('address', $fieldArr) ||
                array_key_exists('zip', $fieldArr) ||
                array_key_exists('city', $fieldArr) ||
                array_key_exists('country', $fieldArr);
    }

    /**
     * Returns true, if the given fieldArray of a tx_workshops_domain_model_location record contains manual geo coordinate
     * updates. Geocoding will not be used, if the user manually specified coordinates.
     *
     * @param array $fieldArr
     * @return bool
     */
    protected function isManualCoordinateUpdate(&$fieldArr)
    {
        return  array_key_exists('longitude', $fieldArr) ||
                array_key_exists('latitude', $fieldArr);
    }
}
