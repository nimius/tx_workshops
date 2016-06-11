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

$lFile = 'LLL:EXT:workshops/Resources/Private/Language/locallang.xlf:';
$lll = $lFile . 'model.instructor.';

$TCA['tx_workshops_domain_model_instructor'] = [
	'ctrl' => [
		'title' => $lFile . 'model.instructor',
		'label' => 'name',
		'dividers2tabs' => TRUE,
		'searchFields' => 'name, email',
		'iconfile' => 'EXT:workshops/Resources/Public/Icons/Instructor.png'
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
		'abstract' => [
			'label' => $lll . 'property.abstract',
			'config' => [
				'type' => 'text',
				'eval' => 'trim',
				'rows' => 3
            ],
		],
		'email' => [
			'label' => $lll . 'property.email',
			'config' => [
				'type' => 'input',
				'max'  => 255,
				'eval' => 'trim,required'
            ],
		],
		'images' => [
			'label' => $lll . 'property.images',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'images', 
				[]
            ),
		],
		'profile_pid' => [
			'label' => $lll . 'property.profilePid',
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
	],
	'types' => [
		'0' => ['showitem' => 'name, abstract, images, email, profile_pid']
    ]
];