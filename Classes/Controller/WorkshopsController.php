<?php
namespace NIMIUS\Workshops\Controller;

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

use NIMIUS\Workshops\Domain\Model\Category;
use NIMIUS\Workshops\Domain\Model\Workshop;
use NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy;
use NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy;
use NIMIUS\Workshops\Utility\MetaTagUtility;

/**
 * Controller for displaying workshop data.
 */
class WorkshopsController extends AbstractController
{
    /**
     * Workshop listing.
     *
     * Displays a list of workshops, based on an optionally
     * selected category. Also exposes categories for navigation.
     *
     * @param NIMIUS\Workshops\Domain\Model\Category $category
     * @ignorevalidation $category
     * @return void
     */
    public function indexAction(Category $category = null)
    {
        $arguments = $this->request->getArguments();
        if ((int)$arguments['workshop'] > 0) {
            // If a workshop uid is given, redirect to the show action instead.
            $this->forward('show', null, null, ['workshop' => (int)$arguments['workshop']]);
            return;
        }

        $proxy = $this->objectManager->get(WorkshopRepositoryProxy::class);
        $proxy->initializeFromSettings($this->settings);
        if ($category) {
            $proxy->addCategory($category);
        }

        $this->view->assignMultiple([
            'category' => $category,
            'workshops' => $this->workshopRepository->findByProxy($proxy),
        ]);
    }

    /**
     * Workshop detail page.
     *
     * Either displays the provided record (via GET) or, if selected,
     * ignores a possibly given record but displays the chosen one.
     *
     * @param NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @dontvalidate $workshop
     * @return void
     */
    public function showAction(Workshop $workshop = null)
    {
        if ($this->settings['displayMode'] == 'selectedRecord') {
            $workshop = $this->workshopRepository->findByUid((int)$this->settings['workshop']);
        }
        if ($workshop) {
            $proxy = $this->objectManager->get(DateRepositoryProxy::class);
            $proxy->initializeFromSettings($this->settings);
            $proxy->setWorkshop($workshop);

            // Ignore languages for resolution of dates relation since dates are not localized.
            $proxy->setLanguages(null);

            $upcoming = $this->dateRepository->findByProxy($proxy);

            $metaTags = MetaTagUtility::renderTags(MetaTagUtility::extractInformationFromWorkshop($workshop, $upcoming));
            $this->response->addAdditionalHeaderData($metaTags);

            $this->view->assignMultiple([
                'workshop' => $workshop,
                'upcomingDates' => $this->dateRepository->findByProxy($proxy)
            ]);
        }

        $this->view->assign('frontendUser', $this->currentFrontendUser());
    }
}
