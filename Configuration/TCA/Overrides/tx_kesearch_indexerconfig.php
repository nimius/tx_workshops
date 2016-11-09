<?php
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
$tableName = 'tx_kesearch_indexerconfig';

// Add targetpid plugin column to indexerconfig TCA.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    $tableName,
    [
        'tx_workshops_targetpid_plugin' => [
            'label' => 'Plugin type on target page',
            'displayCond' => 'FIELD:type:IN:workshops_workshop',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['Workshops plugin', 'tx_workshops_workshops'],
                    ['Single view plugin', 'tx_workshops_workshopssingleview']
                ]
            ]
        ]
    ]
);

// Add field to type.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    $tableName,
    'tx_workshops_targetpid_plugin',
    '',
    'after:type'
);

// Modify display condition sysfolder field.
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['sysfolder']['displayCond'] .= ',' . \NIMIUS\Workshops\Indexer\KeSearch\WorkshopsIndexer::INDEXER_TYPE;
