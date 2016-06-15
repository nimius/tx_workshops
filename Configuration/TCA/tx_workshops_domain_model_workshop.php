<?php
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

use NIMIUS\Workshops\Domain\Model\Workshop;

$lFile = 'LLL:EXT:workshops/Resources/Private/Language/locallang.xlf:';
$lll = $lFile . 'model.workshop.';

$TCA['tx_workshops_domain_model_workshop'] = [
	'ctrl' => [
		'title' => $lFile . 'model.workshop',
		'label' => 'name',
		'dividers2tabs' => TRUE,
		'enablecolumns' => [
			'disabled' => 'hidden',
        ],
		'requestUpdate' => 'type',
		'sortby' => 'sorting',
		'searchFields' => 'name, identifier, abstract, description',
		'type' => 'type',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
		'iconfile' => 'EXT:workshops/Resources/Public/Icons/Date.png'
    ],
	'interface' => [
		'showRecordFieldList' => 'hidden, identifier, name'
    ],
	'columns' => [
		'hidden' => [
			'label' => 'LLL:EXT:cms/locallang_tca.xlf:pages.nav_hide_checkbox_1_formlabel',
			'config' => [
				'type' => 'check',
				'default' => 0
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
                'foreign_table' => 'tx_workshops_domain_model_workshop',
                'foreign_table_where' => 'AND tx_workshops_domain_model_workshop.pid=###CURRENT_PID### AND tx_workshops_domain_model_workshop.sys_language_uid IN (-1,0)',
                'showIconTable' => FALSE
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
		'type' => [
			'label' => $lll . 'property.type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					[$lll . 'property.type.default', Workshop::TYPE_DEFAULT],
					[$lll . 'property.type.external', Workshop::TYPE_EXTERNAL],
                ],
            ],
        ],
		'internal_url' => [
			'label' => $lll . 'property.internalUrl',
			'config' => [
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'maxitems' => 1,
				'minitems' => 0,
				'show_thumbs' => 1,
				'softref' => 'typolink',
				'wizards' => [
					'suggest' => [
						'type' => 'suggest',
                    ],
                ],
            ],
        ],
		'external_url' => [
			'label' => $lll . 'property.externalUrl',
			'displayCond' => 'FIELD:type:=:' . Workshop::TYPE_EXTERNAL,
			'config' => [
				'type' => 'input',
				'eval' => 'required',
				'softref' => 'typolink',
				'wizards' => [
					'link' => [
						'type' => 'popup',
						'title' => 'LLL:EXT:cms/locallang_ttc.xml:header_link_formlabel',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                    ],
                ],
            ],
        ],
		'identifier' => [
			'label' => $lll . 'property.identifier',
			'config' => [
				'type' => 'input',
				'max'  => 255,
				'eval' => 'trim'
            ],
        ],
		'name' => [
			'label' => $lll . 'property.name',
			'config' => [
				'type' => 'input',
				'max'  => 255,
				'eval' => 'trim,required'
            ],
        ],
		'abstract' => [
			'label' => $lll . 'property.abstract',
			'config' => [
				'type' => 'text',
				'eval' => 'trim',
				'rows' => 3
            ],
        ],
		'description' => [
			'label' => $lll . 'property.description',
			'config' => [
				'type' => 'text',
				'eval' => 'trim',
				'rows' => 10,
				'wizards' => [
					'_PADDING' => 2,
					'RTE' => [
						'notNewRecords' => 1,
						'RTEonly' => 1,
						'type' => 'script',
						'title' => 'Full screen Rich Text Editing',
						'icon' => 'wizard_rte2.gif',
						'module' => [
							'name' => 'wizard_rte',
                        ],
                    ],
                ],
            ],
        ],
		'price' => [
			'label' => $lll . 'property.price',
			'config' => [
				'type' => 'input',
				'eval' => 'double2,null',
				'size' => 8,
            ],
        ],
		'dates' => [
			'label' => $lll . 'property.dates',
			'displayCond' => 'FIELD:type:=:' . Workshop::TYPE_DEFAULT,
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_workshops_domain_model_date',
				'foreign_field' => 'workshop',
				'appearance' => [
					'collapseAll' => 1,
					'expandSingle' => 1
                ],
            ],
        ],
		'categories' => [
			'label' => $lll . 'property.categories',
			'l10n_mode' => 'mergeIfNotBlank',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectTree',
				'treeConfig' => [
					'parentField' => 'parent',
					'appearance' => [
						'showHeader' => TRUE,
						'allowRecursiveMode' => TRUE,
						'expandAll' => TRUE,
						'maxLevels' => 99,
                    ],
                ],
				'MM' => 'sys_category_record_mm',
				'MM_match_fields' => [
					'fieldname' => 'categories',
					'tablenames' => 'tx_workshops_domain_model_workshop',
                ],
				'MM_opposite_field' => 'items',
				'foreign_table' => 'sys_category',
				'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 0,
				'maxitems' => 20,
            ],
        ],
		'related_workshops' => [
			'label' => $lll . 'property.relatedWorkshops',
			'config' => [
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_workshops_domain_model_workshop',
				'foreign_table' => 'tx_workshops_domain_model_workshop',
				'foreign_sortby' => 'sorting',
				'MM_opposite_field' => 'uid',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 100,
				'MM' => 'tx_workshops_domain_model_related_workshop',
				'wizards' => [
					'suggest' => [
						'type' => 'suggest',
                    ],
                ],
            ],
        ],
		'images' => [
			'label' => $lll . 'property.images',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'images', 
				[]
            ),
        ],
		'files' => [
			'label' => $lll . 'property.files',
			'displayCond' => 'FIELD:type:>=:' . Workshop::TYPE_DEFAULT,
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'files', 
				[]
            ),
        ],
    ],
	'types' => [
		Workshop::TYPE_DEFAULT => [
			'showitem' => '
				--div--;' . $lll . 'div.general,
				hidden, sys_language_uid, l10n_parent,
                type, identifier, name, internal_url, abstract, description, price,
				--div--;' . $lll . 'div.relations,
				categories, images, files, related_workshops,
				--div--;' . $lll . 'div.dates,
				dates
			',
        ],
		Workshop::TYPE_EXTERNAL => [
			'showitem' => '
				--div--;' . $lll . 'div.general,
				hidden, sys_language_uid, l10n_parent,
                type, identifier, name, abstract, price,
				external_url,
				--div--;' . $lll . 'div.relations,
				categories, images, 
			',
        ],
    ],
];