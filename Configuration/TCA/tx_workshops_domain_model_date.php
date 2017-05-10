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

use NIMIUS\Workshops\Domain\Model\Date;

$lFile = 'LLL:EXT:workshops/Resources/Private/Language/locallang.xlf:';
$lll = $lFile . 'model.date.';
$emConf = NIMIUS\Workshops\Utility\ConfigurationUtility::getExtensionConfiguration();

return [
    'ctrl' => [
        'title' => $lFile . 'model.date',
        'label' => 'begin_at',
        'label_userFunc' => 'NIMIUS\\Workshops\\UserFunc\\TcaLabelling->date',
        'hideTable' => true,
        'dividers2tabs' => true,
        'requestUpdate' => 'type, payment_type',
        'type' => 'type',
        'tstamp' => 'updated_at',
        'iconfile' => 'EXT:workshops/Resources/Public/Icons/Time.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, begin_at, end_at'
    ],
    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:cms/locallang_tca.xlf:pages.nav_hide_checkbox_1_formlabel',
            'displayCond' => 'FIELD:parent:=:0',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'type' => [
            'label' => $lll . 'property.type',
            'displayCond' => 'FIELD:parent:=:0',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$lll . 'property.type.single', Date::TYPE_SINGLE],
                    [$lll . 'property.type.multiple', Date::TYPE_MULTIPLE]
                ],
                'default' => Date::TYPE_SINGLE
            , ]
        ],
        'payment_type' => [
            'label' => $lll . 'property.paymentType',
            'displayCond' => 'FIELD:parent:=:0',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$lll . 'property.paymentType.free', Date::PAYMENT_TYPE_FREE],
                    [$lll . 'property.paymentType.prepay', Date::PAYMENT_TYPE_PREPAY],
                    [$lll . 'property.paymentType.boxOffice', Date::PAYMENT_TYPE_BOX_OFFICE],
                    [$lll . 'property.paymentType.external', Date::PAYMENT_TYPE_EXTERNAL],
                ],
            ],
        ],
        'price' => [
            'label' => $lll . 'property.price',
            'displayCond' => 'FIELD:payment_type:!=:' . Date::PAYMENT_TYPE_FREE,
            'config' => [
                'type' => 'input',
                'eval' => 'double2',
                'size' => 8,
            ],
        ],
        'external_payment_url' => [
            'label' => $lll . 'property.externalPaymentUrl',
            'displayCond' => 'FIELD:payment_type:=:' . Date::PAYMENT_TYPE_EXTERNAL,
            'config' => [
                'type' => 'input',
                'eval' => 'required',
                'softref' => 'typolink',
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xml:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act' => 'page'
                            ],
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                    ],
                ],
            ],
        ],
        'parent' => [
            'config' => [
                'type' => 'passthrough',
                'default' => 0,
                'foreign_table' => 'tx_workshops_domain_model_date',
            ],
        ],
        'dates' => [
            'label' => $lll . 'property.dates',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_workshops_domain_model_date',
                'foreign_field' => 'parent',
                'appearance' => [
                    'expandSingle' => 1
                ],
                'behaviour' => [
                    'localizationMode' => 'keep'
                ]
            ]
        ],
        'workshop' => [
            /* This field is required when creating new dates through the BE module
             * (which loads the date form only) as without this field, the date record
             * would be orphaned, as workshop field is missing. In this case, the workshop
             * is provided to the TCA form through the URL.
             *
             * However, when creating nested date records outside the BE module,
             * child date records would also display the workshop field. This would
             * mess up data integrity, as single dates belonging to multiple dates must not have
             * a workshop foreign key set. The IRRE TCA handling automagically removes the workshop
             * field.
             */
            'label' => $lFile . 'model.workshop',
            'displayCond' => 'FIELD:workshop:REQ:true',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_workshops_domain_model_workshop'
            ]
        ],
        'registrations' => [
            'label' => $lll . 'property.registrations',
            'displayCond' => 'FIELD:parent:=:0',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_workshops_domain_model_registration',
                'foreign_field' => 'workshop_date',
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1
                ],
            ],
        ],
        'begin_at' => [
            'label' => $lll . 'property.beginAt',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'max' => 20,
                'eval' => 'datetime, required',
                'default' => 0,
            ],
        ],
        'end_at' => [
            'label' => $lll . 'property.endAt',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'max' => 20,
                'eval' => 'datetime, required',
                'default' => 0,
            ],
        ],
        'notes' => [
            'label' => $lll . 'property.notes',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
                'rows' => 4,
            ],
        ],
        'updated_at' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
    'types' => [
        Date::TYPE_SINGLE => [
            'showitem' => '
				--div--;' . $lll . 'div.general,
				hidden, type, workshop, location, instructor, begin_at, end_at, registration_deadline_at, notes,
				--div--;' . $lll . 'div.settings,
				minimum_attendance_enabled, minimum_attendance,
				maximum_attendance_enabled, maximum_attendance,
                --div--;' . $lll . 'div.payment,
                payment_type, external_payment_url, price,
				--div--;' . $lll . 'div.registrations,
				registrations
			'
        ],
        Date::TYPE_MULTIPLE => [
            'showitem' => '
				--div--;' . $lll . 'div.general,
				hidden, type, workshop, dates, location, instructor, registration_deadline_at, notes,
				--div--;' . $lll . 'div.settings,
				minimum_attendance_enabled, minimum_attendance,
				maximum_attendance_enabled, maximum_attendance,
                --div--;' . $lll . 'div.payment,
                payment_type, external_payment_url, price,
				--div--;' . $lll . 'div.registrations,
				registrations
			'
        ],
    ],
];

if ((bool)$emConf['locations.']['enable']) {
    $TCA['tx_workshops_domain_model_date']['columns'] += [
        'location' => [
            'label' => $lFile . 'model.location',
            'displayCond' => 'FIELD:parent:=:0',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_workshops_domain_model_location',
                'maxitems' => 1,
                'items' => [
                    [null, null]
                ],
            ],
        ],
    ];
}

if ((bool)$emConf['instructors.']['enable']) {
    $TCA['tx_workshops_domain_model_date']['columns'] += [
        'instructor' => [
            'label' => $lFile . 'model.instructor',
            'displayCond' => 'FIELD:parent:=:0',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_workshops_domain_model_instructor',
                'foreign_table' => 'tx_workshops_domain_model_instructor',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
            ],
        ],
    ];
}

if ((bool)$emConf['attendees.']['maximumAttendance.']['enable']) {
    $defaultValue = $emConf['attendees.']['maximumAttendance.']['defaultValue'];

    $TCA['tx_workshops_domain_model_date']['columns'] += [
        'maximum_attendance_enabled' => [
            'label' => $lll . 'property.maximumAttendanceEnabled',
            'config' => [
                'type' => 'check',
                'default' => 1,
            ],
        ],
        'maximum_attendance' => [
            'label' => $lll . 'property.maximumAttendance',
            'config' => [
                'type' => 'input',
                'eval' => 'int',
                'default' => $defaultValue,
            ],
        ],
    ];
}

if ((bool)$emConf['attendees.']['minimumAttendance.']['enable']) {
    $defaultValue = $emConf['attendees.']['minimumAttendance.']['defaultValue'];

    $TCA['tx_workshops_domain_model_date']['columns'] += [
        'minimum_attendance_enabled' => [
            'label' => $lll . 'property.minimumAttendanceEnabled',
            'config' => [
                'type' => 'check',
                'default' => 1,
            ],
        ],
        'minimum_attendance' => [
            'label' => $lll . 'property.minimumAttendance',
            'config' => [
                'type' => 'input',
                'eval' => 'int',
                'default' => $defaultValue,
            ],
        ],
    ];
}

if ((bool)$emConf['attendees.']['registrationDeadline.']['enable']) {
    $TCA['tx_workshops_domain_model_date']['columns'] += [
        'registration_deadline_at' => [
            'label' => $lll . 'property.registrationDeadlineAt',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'max' => 20,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
    ];
}
