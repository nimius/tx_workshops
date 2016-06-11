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

$EM_CONF[$_EXTKEY] = [
    'title' => 'Workshop management',
    'description' => 'Flexible workshop and courses management.',
    'category' => 'fe',
    'version' => '1.0.0',
    'state' => 'beta',
    'author' => 'NIMIUS',
    'author_email' => 'info@nimius.net',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-7.6.99',
            'static_info_tables' => '7.6.0-7.6.99',
        ],
        'suggests' => [
            'geocoding' => '1.2.0-1.2.99',
            'scheduler' => '7.6.0-7.6.99'
        ],
    ],
];