<?php
namespace NIMIUS\Workshops\ViewHelpers\Form;

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

use NIMIUS\Workshops\Utility\ConfigurationUtility;

/**
 * Label view helper.
 *
 * Renders labels for form fields.
 */
class LabelViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string
     */
    protected $tagName = 'label';

    /**
     * @var string
     */
    protected $append = '';

    /**
     * Class initializer.
     *
     * @todo allow translation of content in title attribute
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->settings = ConfigurationUtility::getTyposcriptConfiguration();
        if ($this->settings['form.']['appendToLabelIfRequiredField']) {
            $this->append = ' ' . $this->settings['form.']['appendToLabelIfRequiredField'];
        } else {
            $this->append = ' <abbr title="required">*</abbr>';
        }
    }

    /**
     * Arguments initialization.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('for', 'string', 'Id of the form element this label belongs to.', false);
    }

    /**
     * Rendering method.
     *
     * Using $property as an argument here instead of registering it in initializeArguments()
     * prevents registering the property twice, which results in an error.
     *
     * @param string $property
     * @return string
     */
    public function render($property)
    {
        $content = $this->renderChildren();
        if (strpos($property, '.') !== false) {
            $property = sscanf($property, 'additionalFields.%s')[0];
            $validationSettings = $this->settings['registration.']['validation.']['additionalFields.'][$property . '.'];
        } else {
            $validationSettings = $this->settings['registration.']['validation.'][$property . '.'];
        }
        if ($validationSettings) {
            $content .= $this->append;
        }
        $this->tag->setContent($content);
        return $this->tag->render();
    }
}
