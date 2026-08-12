<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Utility;

use TYPO3\CMS\Backend\Tree\View\PageTreeView;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Page Utility class
 */
class Page
{

    /**
     * Return a page tree
     *
     * @param int $pageUid page to start with
     * @param int $treeLevel count of levels
     * @throws \Exception
     * @deprecated not in use
     */
    public static function pageTree($pageUid, $treeLevel): PageTreeView
    {
        $pageUid = (int)$pageUid;
        if ($pageUid === 0 && !self::getBackendUser()->isAdmin()) {
            if ((new Typo3Version())->getMajorVersion() >= 14) {
                $mounts = self::getBackendUser()->getWebmounts();
            } else {
                $mounts = self::getBackendUser()->returnWebmounts();
            }
            $pageUid = array_shift($mounts);
        }

        /* @var $tree PageTreeView */
        $tree = GeneralUtility::makeInstance(PageTreeView::class);
        $tree->init('AND ' . self::getBackendUser()->getPagePermsClause(1));

        $treeStartingRecord = BackendUtility::getRecord('pages', $pageUid);
        BackendUtility::workspaceOL('pages', $treeStartingRecord);

        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        // Creating top icon; the current page
        $tree->tree[] = [
            'row' => $treeStartingRecord,
            'HTML' => is_array($treeStartingRecord) ? $iconFactory->getIconForRecord('pages', $treeStartingRecord, IconSize::SMALL)->render() : '',
        ];

        $tree->getTree($pageUid, $treeLevel, '');
        return $tree;
    }

    /**
     * Get backend user
     */
    protected static function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
