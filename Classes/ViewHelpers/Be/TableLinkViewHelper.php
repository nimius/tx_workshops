<?php
namespace NIMIUS\Workshops\ViewHelpers\Be;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Table link view helper.
 *
 * Renders a link to a TCA form engine view.
 */
class TableLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $module = 'web_WorkshopsAdministration';
    
    /**
     * @var string
     */
    protected $plugin = 'tx_workshops_web_workshopsadministration';
    
    /**
     * @var string
     */
    protected $tagName = 'a';


    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('urlOnly', 'boolean', 'If true, only the url is rendered instead of the full tag', false);
    }
    
    /**
     * Creates a link to a view to the TCA table, with returnUrl to the current view.
     * 
     * @param string $table The table of the records
     * @param integer $uid The uid for records if key is edit, the pid otherwise
     * @param string $key Action key (new, edit)
     * @param array $defaultValues Default values for the new record
     * @return string HTML
     */
    public function render($table, $uid, $key = 'edit', $defaultValues = [])
    {
        $request = $this->controllerContext->getRequest();
        
        $returnUrl  = 'index.php?M=' . $this->module;
        $returnUrl .= '&id=' . (int)GeneralUtility::_GET('id');
        $returnUrl .= '&' . $this->plugin . '[controller]=' . $request->getControllerName();
        $returnUrl .= '&' . $this->plugin . '[action]=' . $request->getControllerActionName();
        
        /* Controller and action are taken from the request directly as they do
         * not have to be set in built links, therefore they're not in arguments.
         * However, additional arguments have to be supplied to the returnUrl,
         * otherwise the link is wrong.
         */
        $arguments = $request->getArguments();
        unset($arguments['controller'], $arguments['action']);
        if (count($arguments)) {
            foreach ($arguments as $name => $value) {
                $returnUrl .= '&' . $this->plugin . '[' . $name . ']=' . $value;
            }
        }
        $returnUrl .= '&moduleToken=' . FormProtectionFactory::get()->generateToken('moduleCall', $this->module);
        
        $parameters = [
            'edit[' . $table . '][' . $uid . ']' => $key,
            'returnUrl' => $returnUrl
        ];
        
        if (count($defaultValues)) {
            foreach ($defaultValues as $field => $value) {
                $parameters['defVals[' . $table . '][' . $field . ']'] = $value;
            }
        }
        
        $url = BackendUtility::getModuleUrl('record_edit', $parameters);
        
        if ($this->arguments['urlOnly'] == true) {
            return $url;
        } else {
            $this->tag->addAttribute('href', $url);
            $this->tag->setContent($this->renderChildren());
            $this->tag->forceClosingTag(true);
            return $this->tag->render();
        }
    }

}