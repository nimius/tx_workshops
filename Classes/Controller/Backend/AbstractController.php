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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract extension controller.
 *
 * This extension's base for all backend controllers. Contains shared
 * objects and functionality.
 */
abstract class AbstractController extends \NIMIUS\Workshops\Controller\AbstractController
{

    /**
     * @var int Current page uid
     */
    protected $pageUid;

    /**
     * @var array Page information
     */
    protected $pageInfo;

    /**
     * Action initializer.
     *
     * @return void
     */
    protected function initializeAction()
    {
        $this->pageUid = (int)GeneralUtility::_GP('id');
        $this->pageInfo = BackendUtility::readPageAccess($this->pageUid, $GLOBALS['BE_USER']->getPagePermsClause(1));
    }

    /**
     * Helper method to expose values to the view
     * which are used in almost every view.
     *
     * @return void
     */
    protected function assignDefaults()
    {
        $this->view->assign('pageUid', $this->pageUid);
    }
}
