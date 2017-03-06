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

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Configure 'Workshops' plugin.
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NIMIUS.' . $_EXTKEY,
    'Workshops',
    [
        'Workshops' => 'index, show',
        'Registrations' => 'new, create, confirm',
        'Locations' => 'show'
    ],
    [
        'Workshops' => 'index, show',
        'Registrations' => 'new, create, confirm'
    ]
);

// Configure 'Workshops Single View' plugin.
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NIMIUS.' . $_EXTKEY,
    'WorkshopsSingleView',
    [
        'Workshops' => 'show',
        'Registrations' => 'new, create, confirm',
        'Locations' => 'show'
    ],
    [
        'Workshops' => 'show',
        'Registrations' => 'new, create, confirm'
    ]
);

// Configure 'Dates' plugin.
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NIMIUS.' . $_EXTKEY,
    'Dates',
    [
        'Dates' => 'index',
    ],
    [
        'Dates' => 'index',
    ]
);

// Configure 'Upcoming Dates Teaser' plugin.
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NIMIUS.' . $_EXTKEY,
    'UpcomingDatesTeaser',
    [
        'Dates' => 'upcoming',
    ],
    [
        'Dates' => 'upcoming',
    ]
);

// Configure 'Exports' plugin.
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NIMIUS.' . $_EXTKEY,
    'Exports',
    [
        'Exports' => 'iCalendar',
    ],
    []
);

// Register extbase command controllers for delivering notifications.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \NIMIUS\Workshops\Command\NotificationCommandController::class;

// Custom TCE form evaluators.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\NIMIUS\Workshops\Evaluation\LongitudeEvaluation::class] = '';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\NIMIUS\Workshops\Evaluation\LatitudeEvaluation::class] = '';
