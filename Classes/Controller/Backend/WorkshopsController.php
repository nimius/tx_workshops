<?php
namespace NIMIUS\Workshops\Controller\Backend;

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

use NIMIUS\Workshops\Domain\Proxy\DateRepositoryProxy;
use NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy;

/**
 * Backend controller.
 *
 * @todo display list of upcoming dates in the next days w/ infos (attendeees required etc)
 */
class WorkshopsController extends AbstractController
{

    /**
     * Index action.
     *
     * Displays a list of workshops.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->assignDefaults();
        $data = [];
        $workshopProxy = $this->objectManager->get(WorkshopRepositoryProxy::class);
        $workshopProxy->setPid($this->pageUid);
        $workshops = $this->workshopRepository->findByProxy($workshopProxy);
        foreach ($workshops as $workshop) {
            $data[] = [
                'workshop' => $workshop,
                'nextDate' => $this->dateRepository->findNextUpcomingForWorkshop($workshop)
            ];
        }
        $this->view->assign('workshops', $data);
    }

    /**
     * Show action.
     *
     * Displays a workshop.
     *
     * @param \NIMIUS\Workshops\Domain\Model\Workshop $workshop
     * @return void
     */
    public function showAction(\NIMIUS\Workshops\Domain\Model\Workshop $workshop)
    {
        $this->assignDefaults();
        $dateProxy = $this->objectManager->get(DateRepositoryProxy::class);
        $dateProxy->setPid($this->pageUid);
        $dateProxy->setWorkshop($workshop);
        $this->view->assignMultiple([
            'workshop' => $workshop,
            'upcomingDates' => $this->dateRepository->findByProxy($dateProxy)
        ]);
    }
}
