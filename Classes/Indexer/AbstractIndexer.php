<?php
namespace NIMIUS\Workshops\Indexer;

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

use NIMIUS\Workshops\Domain\Repository\WorkshopRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Abstract indexer class.
 */
abstract class AbstractIndexer
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;

    /**
     * @var \NIMIUS\Workshops\Domain\Repository\WorkshopRepository
     */
    protected $workshopRepository;

    /**
     * Class constructor.
     *
     * As ke_search currently does not really work the "extbase" way
     * and is missing DI, dependencies are injected manually.
     *
     * @return void
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->workshopRepository = $this->objectManager->get(WorkshopRepository::class);
    }
}
