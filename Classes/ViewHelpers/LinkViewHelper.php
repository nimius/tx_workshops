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
use NIMIUS\Workshops\Service\WorkshopUrlService;
use NIMIUS\Workshops\Utility\ConfigurationUtility;

/**
 * View helper for rendering links based on the workshop type and settings.
 */
class LinkViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\PageViewHelper
{   

    /**
     * Renders a detail / more link to the given workshop.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @param array $settings
     * @param array $typolinkConfiguration
     * @return string
     */
    public function render(Workshop $workshop = null, array $settings = [], $typolinkConfiguration = [])
    {
        if (!$workshop) {
            return;
        }

        $urlService = $this->objectManager->get(WorkshopUrlService::class);
        $urlService->setObject($workshop);
        $urlService->setSettings(ConfigurationUtility::getTyposcriptConfiguration());
        $urlService->setTypolinkConfiguration($typolinkConfiguration);
        $url = $urlService->render();

        if ($typolinkConfiguration['returnLast']) {
            // Directly return, as the result is either an url or a configuration array.
            return $url;
        }

        if ($workshop->getIsExternal()) {
            $this->tag->addAttribute('target', '_blank');
        }

        $this->tag->addAttribute('href', $url);
        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }

}