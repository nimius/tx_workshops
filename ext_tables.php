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

if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

$emConf = \NIMIUS\Workshops\Utility\ConfigurationUtility::getExtensionConfiguration();
$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$languagePath = 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:';

/**
 * Register 'Workshops' plugin.
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NIMIUS.' . $_EXTKEY,
    'Workshops',
    $languagePath . 'plugin.workshops'
);
$pluginSignature = strtolower($extensionName) . '_workshops';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:' . $_EXTKEY.'/Configuration/FlexForm/Workshops.xml'
);

/**
 * Register 'WorkshopsSingleView' plugin.
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NIMIUS.' . $_EXTKEY,
    'WorkshopsSingleView',
    $languagePath . 'plugin.workshopssingleview'
);
$pluginSignature = strtolower($extensionName) . '_workshopssingleview';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForm/WorkshopsSingleView.xml'
);

/**
 * Register 'Dates' plugin.
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NIMIUS.' . $_EXTKEY,
    'Dates',
    $languagePath . 'plugin.dates'
);
$pluginSignature = strtolower($extensionName) . '_dates';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:' . $_EXTKEY.'/Configuration/FlexForm/Dates.xml'
);

/**
 * Register 'UpcomingDatesTeaser' plugin.
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NIMIUS.' . $_EXTKEY,
    'UpcomingDatesTeaser',
    $languagePath . 'plugin.upcomingdatesteaser'
);
$pluginSignature = strtolower($extensionName) . '_upcomingdatesteaser';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForm/UpcomingDatesTeaser.xml'
);

/**
 * Register backend module.
 */
if (TYPO3_MODE == 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'NIMIUS.' . $_EXTKEY,
        'web',
        'Administration',
        '',
        [
            'Backend\\Workshops' => 'index, show',
            'Backend\\Registrations' => 'index, show',
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/Date.png',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf',
        ]
    );
}

$tcaConfigurationPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/tx_workshops_domain_model_';

// Register workshops table.
$TCA['tx_workshops_domain_model_workshop']['ctrl'] = [
    'dynamicConfigFile' => $tcaConfigurationPath . 'workshop.php'
];

// Register date table.
$TCA['tx_workshops_domain_model_date']['ctrl'] = [
    'dynamicConfigFile' => $tcaConfigurationPath . 'date.php'
];

// Register registration table.
$TCA['tx_workshops_domain_model_registration']['ctrl'] = [
    'dynamicConfigFile' => $tcaConfigurationPath . 'registration.php'
];

// Register location table if feature is enabled.
if ((bool)$emConf['locations.']['enable']) {
    $TCA['tx_workshops_domain_model_location']['ctrl'] = [
        'dynamicConfigFile' => $tcaConfigurationPath . 'location.php'
    ];
}

// Register instructor table if feature is enabled.
if ((bool)$emConf['instructors.']['enable']) {
    $TCA['tx_workshops_domain_model_instructor']['ctrl'] = [
        'dynamicConfigFile' => $tcaConfigurationPath . 'instructor.php'
    ];
}

// Add static extension TypoScript template.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Default TypoScript');

// Add DrawItem Hook to add information to content element previews.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][$_EXTKEY] = \NIMIUS\Workshops\Hook\PageLayoutViewDrawItemHook::class;

// Hook into datamapper for backend data processing.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['EXT:NIMIUS.' . $_EXTKEY] = \NIMIUS\Workshops\Hook\DataMapperHook::class;

// Register status provider for reports module.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['EXT:NIMIUS.' . $_EXTKEY][] = \NIMIUS\Workshops\Report\Status\ConfigurationStatus::class;

// Register indexer for ext:ke_search.
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] = \NIMIUS\Workshops\Indexer\KeSearch\WorkshopsIndexer::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] = \NIMIUS\Workshops\Indexer\KeSearch\WorkshopsIndexer::class;