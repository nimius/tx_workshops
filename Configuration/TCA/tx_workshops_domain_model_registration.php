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

use NIMIUS\Workshops\Utility\TCAUtility;

$lFile = 'LLL:EXT:workshops/Resources/Private/Language/locallang.xlf:';
$lll = $lFile . 'model.registration.property.';

$TCA['tx_workshops_domain_model_registration'] = [
	'ctrl' => [
		'title' => $lFile . 'model.registration',
		'label' => 'attendee',
		'crdate' => 'crdate',
		'hideTable' => true,
		'iconfile' => 'EXT:workshops/Resources/Public/Icons/Vcard.png'
	],
	'interface' => [
		'showRecordFieldList' => 'frontend_user'
	],
	'columns' => [
		'workshop_date' => [
			'label' => $lFile . 'model.date',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_workshops_domain_model_date',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1,
			],
		],
		'frontend_user' => [
			'label' => 'LLL:EXT:cms/locallang_tca.xlf:fe_users',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'maxitems' => 1,
				'items' => [
					[null, null]
                ],
            ],
		],
		'language' => [
			'label' => $lll . 'language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'minitems' => 0,
				'maxitems' => 1,
				'items' => [
					[null, null]
                ],
            ],
		],
		'company' => [
			'label' => $lll . 'company',
			'config' => [
				'type' => 'input',
			],
		],
		'first_name' => [
			'label' => $lll . 'firstName',
			'config' => [
				'type' => 'input',
				'eval' => TCAUtility::registrationValidationEvalValue('firstName'),
			],
		],
		'last_name' => [
			'label' => $lll . 'lastName',
			'config' => [
				'type' => 'input',
				'eval' => TCAUtility::registrationValidationEvalValue('lastName'),
			],
		],
		'address' => [
			'label' => $lll . 'address',
			'config' => [
				'type' => 'input',
				TCAUtility::registrationValidationEvalValue('address'),
			],
		],
		'zip' => [
			'label' => $lll . 'zip',
			'config' => [
				'type' => 'input',
				TCAUtility::registrationValidationEvalValue('zip'),
			],
		],
		'city' => [
			'label' => $lll . 'city',
			'config' => [
				'type' => 'input',
				TCAUtility::registrationValidationEvalValue('city'),
			],
		],
		'country' => [
			'label' => $lll . 'country',
			'config' => [
				'type' => 'input',
				TCAUtility::registrationValidationEvalValue('country'),
			],
		],
		'telephone' => [
			'label' => $lll . 'telephone',
			'config' => [
				'type' => 'input',
				TCAUtility::registrationValidationEvalValue('telephone'),
			],
		],
		'email' => [
			'label' => $lll . 'email',
			'config' => [
				'type' => 'input',
				'eval' => 'required'
			],
		],
		'confirmation_sent_at' => [
			'label' => $lll . 'confirmationSentAt',
			'config' => [
				'type' => 'input',
				'eval' => 'datetime',
				'readOnly' => true,
			],
		],
		'additional_fields_string' => [
			'config' => [
				'type' => 'passthrough',
            ],
		],
		'crdate' => [
			'label' => $lll . 'crdate',
			'config' => [
				'type' => 'input',
				'eval' => 'datetime',
				'readOnly' => true,
            ],
		],
	],
	'types' => [
		'0' => [
            'showitem' => '
                frontend_user, company, first_name, last_name, address, zip, city, country,
                email, telephone, language,  confirmation_sent_at, crdate'
            ]
        ]
];