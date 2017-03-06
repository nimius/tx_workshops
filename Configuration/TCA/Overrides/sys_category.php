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
$tableName = 'sys_category';
$xlf = 'LLL:EXT:workshops/Resources/Private/Language/locallang.xlf:';

// Add single pid column to sys_category TCA.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    $tableName,
    [
        'tx_workshops_detail_pid' => [
            'label' => $xlf . 'model.category.property.detailPid',
            'l10n_mode' => 'mergeIfNotBlank',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'show_thumbs' => 1,
                'default' => 0,
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'searchWholePhrase' => true
                        ]
                    ],
                ],
            ]
        ],
        'tx_workshops_images' => [
            'label' => $xlf . 'model.category.property.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'tx_workshops_images',
                []
            ),
        ],
    ]
);

// Add workshops palette to TCA types.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    $tableName,
    '--div--;Workshops, tx_workshops_images, tx_workshops_detail_pid'
);
