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
$lFile = 'LLL:EXT:workshops/Resources/Private/Language/locallang.xlf:';
$lll = $lFile . 'model.location.';

$TCA['tx_workshops_domain_model_location'] = [
    'ctrl' => [
        'title' => $lFile . 'model.location',
        'label' => 'name',
        'dividers2tabs' => true,
        'searchFields' => 'name, address, city',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'iconfile' => 'EXT:workshops/Resources/Public/Icons/Map.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'name'
    ],
    'columns' => [
        'name' => [
            'label' => $lll . 'property.name',
            'config' => [
                'type' => 'input',
                'max'  => 255,
                'eval' => 'trim,required'
            ],
        ],
        'address' => [
            'label' => $lll . 'property.address',
            'config' => [
                'type' => 'text',
                'rows' => 3,
                'eval' => 'trim'
            ],
        ],
        'zip' => [
            'label' => $lll . 'property.zip',
            'config' => [
                'type' => 'input',
                'max'  => 10,
                'eval' => 'trim'
            ],
        ],
        'city' => [
            'label' => $lll . 'property.city',
            'config' => [
                'type' => 'input',
                'max'  => 255,
                'eval' => 'trim'
            ],
        ],
        'country' => [
            'label' => $lll . 'property.country',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'static_countries',
                'minitems'  => 0,
                'maxitems' => 1,
            ],
        ],
        'latitude' => [
            'label' => $lll . 'property.latitude',
            'config' => [
                'type' => 'input',
                'max'  => 12,
                'eval' => \NIMIUS\Workshops\Evaluation\LatitudeEvaluation::class
            ],
        ],
        'longitude' => [
            'label' => $lll . 'property.longitude',
            'config' => [
                'type' => 'input',
                'max'  => 12,
                'eval' => \NIMIUS\Workshops\Evaluation\LongitudeEvaluation::class
            ],
        ],
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_workshops_domain_model_location',
                'foreign_table_where' => 'AND tx_workshops_domain_model_location.pid=###CURRENT_PID### AND tx_workshops_domain_model_location.sys_language_uid IN (-1,0)',
                'showIconTable' => false
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'sys_language_uid, l10n_parent, name, address, zip, city, country, latitude, longitude']
    ]
];
