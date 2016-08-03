<?php
namespace NIMIUS\Workshops\Indexer\KeSearch;

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
use NIMIUS\Workshops\Domain\Proxy\WorkshopRepositoryProxy;
use NIMIUS\Workshops\Indexer\AbstractIndexer;
use NIMIUS\Workshops\ViewHelpers\LinkViewHelper;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Workshops indexer for ext:ke_search.
 */
class WorkshopsIndexer extends AbstractIndexer
{

    /**
     * @var string Indexer type.
     */
    const INDEXER_TYPE = 'workshops_workshop';


	/**
	 * Register indexer configuration.
	 *
	 * Called from within ke_search.
	 *
	 * @param array &$params
	 * @param \object $pObj
	 * @return void
	 */
	public function registerIndexerConfiguration(&$params, $pObj) {
		$params['items'][] = array(
			'Workshops (workshops)',
			self::INDEXER_TYPE,
			ExtensionManagementUtility::extRelPath('workshops') . 'Resources/Public/Icons/Date.png'
		);
	}

	/**
	 * Indexing method.
	 *
	 * Called from within ke_search.
	 *
	 * @param array &$indexerConfiguration Configuration from TYPO3 Backend
	 * @param \object &$indexerObject Reference to indexer class.
	 * @return void
	 */
	public function customIndexer(&$indexerConfiguration, &$indexerObject)
    {
		if ($indexerConfiguration['type'] != self::INDEXER_TYPE) {
			return;
		}

        $proxy = $this->objectManager->get(WorkshopRepositoryProxy::class);
        $proxy->setPids(GeneralUtility::trimExplode(',', $indexerConfiguration['sysfolder'], TRUE));
        $proxy->setTypes([Workshop::TYPE_DEFAULT]);

        $workshops = $this->workshopRepository->findByProxy($proxy);
        foreach($workshops as $workshop) {
            $linkViewHelper = $this->objectManager->get(LinkViewHelper::class);
            $linkConfiguration = $linkViewHelper->render(
                $workshop, 
                [
                    'targetPid' => (int)$indexerConfiguration['targetpid'],
                    'targetPlugin' => $indexerConfiguration['tx_workshops_targetpid_plugin']
                ],
                ['returnLast' => 'configuration']
            );
            
            $indexContent = $workshop->getName();
            $indexContent .= "\n";
            $indexContent .= $workshop->getAbstract();

			$indexerObject->storeInIndex(
				// Storage PID.
				(int)$indexerConfiguration['storagepid'],

				// Record title.
				$workshop->getName(),

				// Content type
				self::INDEXER_TYPE,

				// Target page of the record's single view.
				$linkConfiguration['parameter'],

				// Indexed content.
				$indexContent,

				// Tags for faceted search.
				'',

				// Query string for detail link.
				$linkConfiguration['additionalParams'],

				// Abstract.
				$workshop->getAbstract(),

				// Record language.
				$workshop->getSysLanguageUid(),

				// Start time.
				NULL,

				// End time.
				NULL,

				// fe_groups.
				NULL,
				
				// Debugging.
				FALSE,
				
				// Additional fields.
				array(
					'orig_uid' => $workshop->getUid(),
					'orig_pid' => $workshop->getPid()
				)
			);
        }
    }

}