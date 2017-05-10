<?php
namespace NIMIUS\Workshops\Service;

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

use NIMIUS\Workshops\Domain\Model\Workshop;

/**
 * Workshop URL service class.
 *
 * Builds URLs to workshops.
 */
class WorkshopUrlService extends AbstractUrlService
{
    /**
     * @var array Default settings, used when preparing settings.
     */
    protected $defaultSettings = [
        'targetPlugin' => 'tx_workshops_workshops'
    ];

    public function modifyTypolinkConfiguration()
    {
        if ($this->object->getIsExternal()) {
            $this->configureForExternalType();
        } else {
            $this->configureForDefaultType();
        }
    }

    /**
     * Configure links to workshops of default type.
     *
     * @param array &$configuration
     * @return void
     */
    protected function configureForDefaultType()
    {
        $this->typolinkConfiguration['useCacheHash'] = true;
        // If the workshops has its own internal URL, link to it.
        if ($this->object->getInternalUrl()) {
            $this->typolinkConfiguration['parameter'] = $this->object->getInternalUrl();
            $this->typolinkConfiguration['additionalParams'] .= '&tx_workshops_workshopssingleview[workshop]=' . $this->object->getUid();
            return;
        }

        /*
         * If the current plugin has given a detail page, utilize that one.
         * Drop controller and action parameters, as the page must
         * contain the single view plugin.
         */
        if ((int)$this->settings['detailPage']) {
            $this->typolinkConfiguration['parameter'] = (int)$this->settings['detailPage'];
            $this->typolinkConfiguration['additionalParams'] .= '&tx_workshops_workshopssingleview[workshop]=' . $this->object->getUid();
            return;
        }

        /*
         * If one of the workshop's categories has a detail pid, take the first
         * one having one for building the internal link to the workshop detail
         * page. Drop controller and action parameters, as the page must contain
         * the single view plugin.
         */
        if (count($this->object->getCategories()) > 0) {
            foreach ($this->object->getCategories() as $category) {
                if ((int)$category->getWorkshopsDetailPid() > 0) {
                    $this->typolinkConfiguration['parameter'] = $category->getWorkshopsDetailPid();
                    $this->typolinkConfiguration['additionalParams'] .= '&tx_workshops_workshopssingleview[workshop]=' . $this->object->getUid();
                    return;
                }
            }
        }

        // If nothing is defined, link to the defined target page and plugin signature combination.
        $this->typolinkConfiguration['parameter'] = $this->settings['targetPid'];
        $this->typolinkConfiguration['additionalParams'] .= '&' . $this->settings['targetPlugin'] . '[workshop]=' . $this->object->getUid();
    }

    /**
     * Configure links to workshops of external type.
     *
     * @param array &$configuration
     * @return void
     */
    protected function configureForExternalType(&$configuration = [])
    {
        $this->typolinkConfiguration['parameter'] = $this->object->getExternalUrl();
    }
}
