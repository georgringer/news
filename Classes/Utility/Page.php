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
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Page Utility class
 */
class Page
{
    /**
     * Set properties of an object/array in cobj->LOAD_REGISTER which can then
     * be used to be loaded via TS with register:name
     *
     * @param string $properties comma separated list of properties
     * @param mixed $object object or array to get the properties
     * @param string $prefix optional prefix
     */
    public static function setRegisterProperties($properties, mixed $object, $prefix = 'news'): void
    {
        if (!empty($properties) && $object !== null) {
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $items = GeneralUtility::trimExplode(',', $properties, true);

            $register = [];
            foreach ($items as $item) {
                $key = $prefix . ucfirst($item);
                if (is_object($object)) {
                    $getter = 'get' . ucfirst($item);
                    try {
                        $value = $object->$getter();
                        if ($value instanceof \DateTime) {
                            $value = $value->getTimestamp();
                        }
                        $register[$key] = $value;
                    } catch (\Exception $e) {
                        $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);
                        $logger->warning($e->getMessage());
                    }
                }
                if (is_array($object)) {
                    $value = $object[$item];
                    $register[$key] = $value;
                }
            }
            $cObj->cObjGetSingle('LOAD_REGISTER', $register);
        }
    }

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
            $mounts = self::getBackendUser()->returnWebmounts();
            $pageUid = array_shift($mounts);
        }

        /* @var $tree PageTreeView */
        $tree = GeneralUtility::makeInstance(PageTreeView::class);
        $tree->init('AND ' . self::getBackendUser()->getPagePermsClause(1));

        $treeStartingRecord = BackendUtility::getRecord('pages', $pageUid);
        BackendUtility::workspaceOL('pages', $treeStartingRecord);

        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $iconSize = (new Typo3Version())->getMajorVersion() >= 13 ? IconSize::SMALL : 'small';

        // Creating top icon; the current page
        $tree->tree[] = [
            'row' => $treeStartingRecord,
            'HTML' => is_array($treeStartingRecord) ? $iconFactory->getIconForRecord('pages', $treeStartingRecord, $iconSize)->render() : '',
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
