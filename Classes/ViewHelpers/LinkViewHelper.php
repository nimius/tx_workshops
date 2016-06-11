<?php
namespace NIMIUS\Workshops\ViewHelpers;

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

/**
 * View helper for rendering links based on the workshop type and settings.
 */
class LinkViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\PageViewHelper
{

    /**
     * @var TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
     */
    protected $cObj;

    /**
     * @var \NIMIUS\Workshops\Domain\Model\Workshop
     */
    protected $workshop;

    /**
     * @var array
     */
    protected $settings;
    
    
    /**
     * Renders a detail / more link to the given workshop.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @param array $localSettings
     * @param array $typolinkConfiguration
     * @return string
     */
    public function render(Workshop $workshop, array $localSettings = [], $typolinkConfiguration = [])
    {
        $this->workshop = $workshop;
        $this->settings = $this->prepareSettings($localSettings);
        unset($workshop, $localSettings);
        $this->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

        switch($this->workshop->getType()) {
            case Workshop::TYPE_DEFAULT:
                if ($this->workshop->getInternalUrl()) {
                    $this->configureForInternalType($configuration);
                } else {
                    $this->configureForDefaultType($configuration);
                }
                break;

            case Workshop::TYPE_EXTERNAL:
                $this->configureForExternalType($configuration);
                break;
        }

        $url = $this->cObj->typoLink_URL($configuration);
        if ($typolinkConfiguration['returnLast'] == 'url') {
            return $url;
        }

        $this->tag->addAttribute('href', $url);
        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }

    /**
     * Helper to prepare settings.
     *
     * @param array $localSettings
     * @return array
     */
    protected function prepareSettings($localSettings)
    {
        $typoscriptSettings = \NIMIUS\Workshops\Utility\ConfigurationUtility::getTyposcriptConfiguration();
        return array_merge($typoscriptSettings, $localSettings);
    }

    /**
     * Configure links to workshops of default type.
     *
     * @param array &$configuration
     * @return void
     */
    protected function configureForDefaultType(&$configuration = [])
    {
        // Link to given detail page, dropping controller and action parameters, as the page must contain the single view plugin.
        if ((int)$this->settings['detailPage']) {
            $configuration['parameter'] = (int)$this->settings['detailPage'];
            $configuration['additionalParams'] .= '&tx_workshops_workshopssingleview[workshop]=' . $this->workshop->getUid();
        } else {
            // Link to the current page.
            $configuration['parameter'] = $GLOBALS['TSFE']->id;
            $configuration['additionalParams'] .= '&tx_workshops_workshops[workshop]=' . $this->workshop->getUid();
        }
    }

    /**
     * Configure links to workshops of internal type.
     *
     * @param array &$configuration
     * @return void
     */
    protected function configureForInternalType(&$configuration = [])
    {
        $configuration['parameter'] = $this->workshop->getInternalUrl();
    }

    /**
     * Configure links to workshops of external type.
     *
     * @param array &$configuration
     * @return void
     */
    protected function configureForExternalType(&$configuration = [])
    {
        $configuration['parameter'] = $workshop->getExternalUrl();
        $this->tag->addAttribute('target', '_blank');
    }

}