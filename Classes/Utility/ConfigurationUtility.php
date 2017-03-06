<?php
namespace NIMIUS\Workshops\Utility;

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

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Utility class to get configurations.
 */
class ConfigurationUtility
{
    /**
     * @var string
     */
    public static $extKey = 'workshops';

    /**
     * Get configuration from TYPO3 configuration variables.
     *
     * @return array
     */
    public static function getExtensionConfiguration()
    {
        $config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][self::$extKey]);
        if (!is_array($config)) {
            $config = [];
        }
        return $config;
    }

    /**
     * Get framework typoscript configuration.
     *
     * @return array
     */
    public static function getFrameworkTyposcriptConfiguration()
    {
        return ObjectUtility::getConfigurationManager()->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
    }

    /**
     * Get email configuration from TYPO3 configuration variables.
     *
     * @return array
     */
    public static function getMailConfiguration()
    {
        return $GLOBALS['TYPO3_CONF_VARS']['MAIL'];
    }

    /**
     * Get typoscript settings.
     *
     * The CONFIGURATION_TYPE_FULL_TYPOSCRIPT type is used and the returned
     * result broken down as CONFIGURATION_TYPE_SETTINGS does not work
     * correctly for command controllers.
     *
     * Additionally, FlexForm settings from within the current plugin
     * are not merged into the settings automatically, which is also
     * done here.
     *
     * Caveat: The return value is different than the one you get from
     * $this->settings inside e.g. a controller! Having a TypoScript
     * setup of "foo = 1, foo.bar = 1", you won't have [foo][_typoScriptNodeValue]
     * and also not have [foo][bar], but [foo] and [foo.][bar].
     *
     * @param string $extKey
     * @return array
     */
    public static function getTyposcriptConfiguration($extKey = null)
    {
        if (!$extKey) {
            $extKey = self::$extKey;
        }
        $signature = 'tx_' . $extKey . '.';

        $configuration = self::getFullTypoScript();
        $settings = (array)$configuration['plugin.'][$signature]['settings.'];

        // A cObj is only given in some cases, mostly in FE context.
        $cObj = ObjectUtility::getConfigurationManager()->getContentObject();
        if ($cObj) {
            $flexFormService = ObjectUtility::getObjectManager()->get('TYPO3\\CMS\\Extbase\\Service\\FlexFormService');
            $flexFormSettings = (array)$flexFormService->convertFlexFormContentToArray($cObj->data['pi_flexform']);
            $settings = array_merge($settings, (array)$flexFormSettings['settings']);
        }
        return $settings;
    }

    /**
     * Shorthand to get the full TypoScript.
     *
     * @return array
     */
    public static function getFullTypoScript()
    {
        return ObjectUtility::getConfigurationManager()->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
    }

    /**
     * Get typoscript configuration for this module.
     *
     * The CONFIGURATION_TYPE_FULL_TYPOSCRIPT type is used and the returned
     * result broken down as CONFIGURATION_TYPE_SETTINGS does not work
     * correctly for command controllers.
     *
     * @return array
     */
    public static function getModuleTyposcriptConfiguration($extKey = null)
    {
        if (!$extKey) {
            $extKey = self::$extKey;
        }
        $signature = 'tx_' . $extKey . '.';
        $configurationManager = ObjectUtility::getConfigurationManager();
        $configuration = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        return (array)$configuration['module.'][$signature];
    }
}
