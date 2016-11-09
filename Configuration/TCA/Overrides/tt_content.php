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

// Modify tt_content for 'Workshops' plugin
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['workshops_workshops'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['workshops_workshops'] = 'select_key';

// Modify tt_content for 'WorkshopsSingleView' plugin.
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['workshops_workshopssingleview'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['workshops_workshopssingleview'] = 'select_key';

// Modify tt_content for 'Dates' plugin.
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['workshops_dates'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['workshops_dates'] = 'select_key';

// Modify tt_content for 'UpcomingDatesTeaser' plugin.
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['workshops_upcomingdatesteaser'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['workshops_upcomingdatesteaser'] = 'select_key';
