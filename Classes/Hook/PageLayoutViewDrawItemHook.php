<?php
namespace NIMIUS\Workshops\Hook;

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
use NIMIUS\Workshops\Utility\ObjectUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Type\Icon\IconState;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\FlexFormService;

/**
 * Page layout view hook class.
 */
class PageLayoutViewDrawItemHook implements \TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface
{
    /**
     * @var array FlexForms settings.
     */
    protected $settings = [];

    /**
     * @var array Content information parts.
     */
    protected $informationParts = [];

    /**
     * Preprocesses the preview rendering of a content element.
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionalities
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     * @return void
     */
    public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
    {
        if ($row['CType'] !== 'list' || substr($row['list_type'], 0, 9) !== 'workshops') {
            return;
        }

        // Disable default output.
        $drawItem = false;

        /*
         * Set plugin header.
         */
        $headerContent = '<strong>Workshops system</strong><br />';
        $pluginName = GeneralUtility::underscoredToLowerCamelCase(substr($row['list_type'], 9));
        $headerContent .= $this->getLanguageLabel('plugin.' . $pluginName . '.description');

        $extensionConfiguration = ConfigurationUtility::getExtensionConfiguration();
        if ((bool)$extensionConfiguration['plugins.']['disablePreview']) {
            return;
        }

        /*
         * Convert FlexForm settings to an array.
         */
        $flexFormService = ObjectUtility::getObjectManager()->get(FlexFormService::class);
        $this->settings = (array)$flexFormService->convertFlexFormContentToArray($row['pi_flexform'])['settings'];

        /*
         * Generate information content.
         */
        $this->buildDisplayModeInformation();
        $this->buildCategoryInformation();
        $this->buildDetailPageInformation();
        $this->buildRecordLimitInformation();

        if (count($this->informationParts) == 0) {
            return;
        }

        $itemContent = '<br/><pre><table>';
        foreach ($this->informationParts as $part) {
            $itemContent .= '<tr>';
            $itemContent .= '<th><strong>' . $part['title'] . '</strong>: </th>';
            $itemContent .= '<td>' . $part['description'] . '</td>';
            $itemContent .= '</tr>';
        }
        $itemContent .= '</table></pre>';
    }

    /**
     * Builds display mode information for plugin preview.
     *
     * @return void
     */
    protected function buildDisplayModeInformation()
    {
        if (empty($this->settings['displayMode'])) {
            return;
        }

        $this->informationParts[] = [
            'title' => $this->getLanguageLabel('flexForm.settings.displayMode'),
            'description' => $this->getLanguageLabel('flexForm.settings.displayMode.' . $this->settings['displayMode'])
        ];

        if ($this->settings['displayMode'] == 'selectedRecord') {
            $workshop = BackendUtility::getRecord(
                'tx_workshops_domain_model_workshop',
                (int)$this->settings['workshop'],
                'name'
            );
            $this->informationParts[] = [
                'title' => $this->getLanguageLabel('model.workshop'),
                'description' => $this->renderIcon('tcarecords-tx_workshops_domain_model_workshop-default') . ' ' . $workshop['name']
            ];
        }
    }

    /**
     * Builds category information for plugin preview.
     *
     * @return void
     */
    protected function buildCategoryInformation()
    {
        if (empty($this->settings['categoryOperator'])) {
            return;
        }

        $categories = [];
        $categoriesUids = GeneralUtility::trimExplode(',', $this->settings['categories'], true);
        foreach ($categoriesUids as $categoryUid) {
            $categoryRecord = BackendUtility::getRecord(
                'sys_category',
                $categoryUid,
                'title'
            );
            $categories[] = $this->renderIcon('mimetypes-x-sys_category') . ' ' . $categoryRecord['title'];
        }
        if (count($categories) == 0) {
            // Category operator is set, but no categories selected;
            return;
        }

        if ($this->settings['categoryOperator'] == 'AND') {
            $this->informationParts[] = [
                'title' => $this->getLanguageLabel('flexForm.settings.categoryOperator'),
                'description' => $this->getLanguageLabel('flexForm.settings.categoryOperator.and')
            ];
        } else {
            $this->informationParts[] = [
                'title' => $this->getLanguageLabel('flexForm.settings.categoryOperator'),
                'description' => $this->getLanguageLabel('flexForm.settings.categoryOperator.and')
            ];
        }
        $this->informationParts[] = [
            'title' => $this->getLanguageLabel('flexForm.settings.categories'),
            'description' => implode('&nbsp;&nbsp;', $categories)
        ];
    }

    /**
     * Builds detail page information for plugin preview.
     *
     * @return void
     */
    protected function buildDetailPageInformation()
    {
        if ((int)$this->settings['detailPage'] == 0) {
            return;
        }

        $page = BackendUtility::getRecord(
            'pages',
            $this->settings['detailPage'],
            'title'
        );
        $this->informationParts[] = [
            'title' => $this->getLanguageLabel('flexForm.settings.detailPage'),
            'description' => $this->renderIcon('apps-pagetree-page-default') . ' ' . $page['title'] . ' [' . $this->settings['detailPage'] . ']'
        ];
    }

    /**
     * Builds information about record limits.
     *
     * @return void
     */
    protected function buildRecordLimitInformation()
    {
        if ((int)$this->settings['recordLimit'] == 0) {
            return;
        }

        $this->informationParts[] = [
            'title' => $this->getLanguageLabel('flexForm.settings.recordLimit'),
            'description' => $this->settings['recordLimit']
        ];
    }

    /**
     * Renders an icon.
     *
     * @param string $identifier
     * @return string
     */
    protected function renderIcon($identifier)
    {
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        return $iconFactory->getIcon($identifier, Icon::SIZE_SMALL, null, IconState::cast(IconState::STATE_DEFAULT))->render();
    }

    /**
     * Retruns language label from given key.
     *
     * @param string $key
     * @return string
     */
    protected function getLanguageLabel($key)
    {
        return $GLOBALS['LANG']->sL('LLL:EXT:workshops/Resources/Private/Language/locallang.xlf:' . $key);
    }
}
