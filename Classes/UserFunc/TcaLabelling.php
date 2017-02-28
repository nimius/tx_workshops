<?php
namespace NIMIUS\Workshops\UserFunc;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * TCA labelling user func.
 *
 * Provides user functions for labelling TCA records
 * where label and label_alt are not sufficient.
 */
class TcaLabelling
{
    /**
     * Labelling for Date records.
     *
     * The fields 'begin_at' and 'end_at' for Date::TYPE_MULTIPLE records are
     * set from its child records through DataMapperHook.
     *
     * @param array &$params
     * @return void
     */
    public function date(&$params)
    {
        $record = BackendUtility::getRecord($params['table'], $params['row']['uid']);
        if ($record['type'] == Date::TYPE_SINGLE) {
            $params['title'] = $this->dateString($record['begin_at'], $record['end_at']);
        } elseif ($record['type'] == Date::TYPE_MULTIPLE) {
            $age = 0;
            $dateUids = GeneralUtility::intExplode(',', $params['row']['dates']);
            $datesCount = count($dateUids);
            foreach ($dateUids as $uid) {
                $subRecord = BackendUtility::getRecord($params['table'], $uid);
                $age += $subRecord['end_at'] - $subRecord['begin_at'];
            }
            $params['title']  = $this->dateString($record['begin_at'], $record['end_at'], [$age, 0]);
            $params['title'] .= ' (' . $datesCount . ' ';
            if ($datesCount == 1) {
                $params['title'] .= LocalizationUtility::translate('model.date', 'workshops');
            } else {
                $params['title'] .= LocalizationUtility::translate('model.date._plural', 'workshops');
            }
            $params['title'] .= ')';
        }
    }

    /**
     * Generate a date string.
     *
     * @param int $begin
     * @param int $end
     * @param array $age Different values than $begin and $end for age calculation
     * @return string
     */
    protected function dateString($begin, $end, $age = null)
    {
        $str  = BackendUtility::datetime($begin);
        $str .= ' - ';
        $str .= BackendUtility::datetime($end);
        $str .= ' (';
        if ($age) {
            $begin  = $age[1];
            $end = $age[0];
        }
        $str .= BackendUtility::calcAge($end - $begin);
        $str .= ')';
        return $str;
    }
}
