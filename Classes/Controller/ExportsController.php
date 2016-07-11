<?php
namespace NIMIUS\Workshops\Controller;

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

use NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy;
use NIMIUS\Workshops\Utility\ConfigurationUtility;
use NIMIUS\Workshops\Utility\ObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Controller for exporting workshop data.
 */
class ExportsController extends AbstractController
{

    /**
     * Export data as iCalendar.
     *
     * @return string
     */
    public function iCalendarAction()
    {
        $proxy = $this->objectManager->get(DateRepositoryProxy::class);
        $proxy->initializeFromSettings($this->settings);
        $proxy->setIgnoreStoragePid(TRUE);

        $view = $this->objectManager->get(StandaloneView::class);
        $basePath = GeneralUtility::getFileAbsFileName('EXT:workshops/Resources/Private/Templates/Exports/'); // TODO configurable
        if (!substr($basePath, -1, 1) == '/') {
            $basePath .= '/';
        }
        $view->setTemplatePathAndFilename($basePath . 'ICalendar.ics');
        $view->setFormat('text');
        $view->getRequest()->setControllerExtensionName(GeneralUtility::underscoredToUpperCamelCase('workshops'));
        $view->assignMultiple([
            'upcomingDates' => $this->dateRepository->findByProxy($proxy),
            'settings' => $this->settings,
            'prodid' => $this->buildProdidProperty()
        ]);
        // Remove whitespace chars and blank lines for the sake of compliance.
        $output = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $view->render());
        return trim($output);
    }

    /**
     * The PRODID format is: Business Name//Product Name//LANGUAGE where LANGUAGE
     * is a 2-char ISO language code.
     *
     * @return string
     */
    protected function buildProdidProperty()
    {
        $language = ConfigurationUtility::getFullTypoScript()['config.']['language'];
        $prodid = trim($this->settings['export']['iCalendar']['businessName'])
            . '//'
            . trim($this->settings['export']['iCalendar']['productName'])
            . '//'
            . strtoupper(trim($language));
        return $prodid;
    }
}