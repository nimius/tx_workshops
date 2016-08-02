<?php
namespace NIMIUS\Workshops\Service;

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

use NIMIUS\Workshops\Utility\ConfigurationUtility;

/**
 * Abstract url service class.
 * 
 * Base for url service classes building urls to records.
 */
abstract class AbstractUrlService
{

    /**
     * @var mixed The object to link to.
     */
    protected $object;

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     * @inject
     */
    protected $contentObject;

    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     * @inject
     */
    protected $signalSlotDispatcher;

    /**
     * @var array Link building settings.
     */
    protected $settings = [];

    /**
     * @var array Typolink configuration.
     */
    protected $typolinkConfiguration = [];


    /**
     * @api
     * @param mixed $object
     * @return void
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @api
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @api
     * @param array $settings
     * @return void
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @api
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @api
     * @param array $typolinkConfiguration
     * @return void
     */
    public function setTypolinkConfiguration(array $typolinkConfiguration)
    {
        $this->typolinkConfiguration = $typolinkConfiguration;
    }

    /**
     * @api
     * @return array
     */
    public function getTypolinkConfiguration()
    {
        return $this->typolinkConfiguration;
    }

    /**
     * Renders a link to the given object.
     *
     * @api
     * @param mixed $object
     * @param array $settings
     * @param array $typolinkConfiguration
     * @return string
     */
    public function render()
    {
        $this->initializeSettings();
        $this->modifyTypolinkConfiguration();
        $this->signalSlotDispatcher->dispatch(__CLASS__, 'urlServiceBeforeFinishRendering', [$this]);
        if ($this->typolinkConfiguration['returnLast'] == 'configuration') {
            return $this->typolinkConfiguration;
        }
        return $this->contentObject->typoLink_URL($this->typolinkConfiguration);
    }

    /**
     * Modify TypoLink configuration for the current UrlService implementation.
     *
     * This modifies the typolinkConfiguration property of the current instance
     * in order to be able to link to the defined object properly.
     *
     * @abstract
     * @return void
     */
    abstract protected function modifyTypolinkConfiguration();

    /**
     * Initialize settings.
     *
     * @param array $localSettings
     * @return array
     */
    protected function initializeSettings()
    {
        /*
         * If no targetPid is given, set the current page as a default value.
         * This allows overriding the target page in e.g. BE context.
         */
        if ((int)$this->settings['targetPid'] == 0) {
            $this->settings['targetPid'] = $GLOBALS['TSFE']->id;
        }

        /*
         * Set a default target plugin signature if none is given. This
         * option is useful when e.g. in BE context where no flexform
         * settings for a detail page is present.
         */
        if (empty($this->settings['targetPlugin']) && !empty($this->defaultSettings['targetPlugin'])) {
            $this->settings['targetPlugin'] = $this->defaultSettings['targetPlugin'];
        }

        $typoscriptSettings = ConfigurationUtility::getTyposcriptConfiguration();
        return array_merge($typoscriptSettings, $localSettings);
    }

}