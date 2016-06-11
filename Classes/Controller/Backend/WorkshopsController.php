<?php
namespace NIMIUS\Workshops\Controller\Backend;

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
	 */
	public function indexAction()
	{
		$this->assignDefaults();
		$data = [];
		$workshops = $this->workshopRepository->findAll($this->pageUid);
		$graceTime = 60 * 60 * 24 * 5;
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
	 */
	public function showAction(\NIMIUS\Workshops\Domain\Model\Workshop $workshop)
	{
		$this->assignDefaults();
		$this->view->assignMultiple([
			'workshop' => $workshop,
			'upcomingDates' => $this->dateRepository->findAllRelevantForWorkshop($workshop)
        ]);
	}

}