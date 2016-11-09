<?php
namespace NIMIUS\Workshops\Domain\Repository;

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

/**
 * Language repository.
 */
class LanguageRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Find currently used language by given uid.
     *
     * @param int $uid
     * @return \NIMIUS\Workshops\Domain\Model\Language|null
     */
    public function findByUid($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        return $query->matching(
            $query->equals('uid', $uid)
        )->setLimit(1)->execute()->toArray()[0];
    }
}
