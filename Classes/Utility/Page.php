<?php

namespace GeorgRinger\News\Utility;

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

/**
 * Page Utility class
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Page {

	/**
	 * Find all ids from given ids and level
	 *
	 * @param string $pidList comma separated list of ids
	 * @param integer $recursive recursive levels
	 * @return string comma separated list of ids
	 */
	public static function extendPidListByChildren($pidList = '', $recursive = 0) {
		$recursive = (int)$recursive;
		if ($recursive <= 0) {
			return $pidList;
		}

		$queryGenerator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Database\\QueryGenerator');
		$recursiveStoragePids = $pidList;
		$storagePids = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $pidList);
		foreach ($storagePids as $startPid) {
			$pids = $queryGenerator->getTreeList($startPid, $recursive, 0, 1);
			if (strlen($pids) > 0) {
				$recursiveStoragePids .= ',' . $pids;
			}
		}
		return $recursiveStoragePids;
	}

	/**
	 * Set properties of an object/array in cobj->LOAD_REGISTER which can then
	 * be used to be loaded via TS with register:name
	 *
	 * @param string $properties comma separated list of properties
	 * @param mixed $object object or array to get the properties
	 * @param string $prefix optional prefix
	 * @return void
	 */
	public static function setRegisterProperties($properties, $object, $prefix = 'news') {
		if (!empty($properties) && !is_null(($object))) {
			$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
			$items = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $properties, TRUE);

			$register = array();
			foreach ($items as $item) {
				$key = $prefix . ucfirst($item);
				try {
					$register[$key] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($object, $item);
				} catch (\Exception $e) {
					\TYPO3\CMS\Core\Utility\GeneralUtility::devLog($e->getMessage(), 'news', \TYPO3\CMS\Core\Utility\GeneralUtility::SYSLOG_SEVERITY_WARNING);
				}
			}
			$cObj->LOAD_REGISTER($register, '');
		}
	}

	/**
	 * Return a page tree
	 *
	 * @param integer $pageUid page to start with
	 * @param integer $treeLevel count of levels
	 * @return \TYPO3\CMS\Backend\Tree\View\PageTreeView
	 * @throws \Exception
	 */
	public static function pageTree($pageUid, $treeLevel) {
		if (TYPO3_MODE !== 'BE') {
			throw new \Exception('Page::pageTree does only work in the backend!');
		}

		/* @var $tree \TYPO3\CMS\Backend\Tree\View\PageTreeView */
		$tree = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Tree\\View\\PageTreeView');
		$tree->init('AND ' . $GLOBALS['BE_USER']->getPagePermsClause(1));

		$treeStartingRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $pageUid);
		\TYPO3\CMS\Backend\Utility\BackendUtility::workspaceOL('pages', $treeStartingRecord);

			// Creating top icon; the current page
		$tree->tree[] = array(
			'row' => $treeStartingRecord,
			'HTML' => \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord('pages', $treeStartingRecord)
		);

		$tree->getTree($pageUid, $treeLevel, '');
		return $tree;
	}

}
