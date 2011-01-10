<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Controller to import news records from tt_news2
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Controller_ImportController extends Tx_News2_Controller_AbstractImportController {
	var $currentPageId = NULL;


	public function indexAction() {
		$recordCount = 0;

		try {
			$recordCount = $this->getRecordCount('tt_news');
		} catch (Exception $e) {
			$this->flashMessageContainer->add($e->getMessage());
		}

		$this->view->assign('recordCount', $recordCount);
	}

	public function importNewsOverviewAction() {
		$recordCount = 0;

		try {
			$recordCount = $this->getRecordCount('tt_news');
		} catch (Exception $e) {
			$this->flashMessageContainer->add($e->getMessage());
		}

		$this->view->assign('recordCount', $recordCount);
	}

	public function importNewsAction() {
		/**
		 * @var Tx_News2_Domain_Repository_NewsRepository
		 */
		$newsRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_NewsRepository');


		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tt_news',
			'exportid = 0 AND deleted=0 AND t3ver_id=0 AND t3ver_wsid = 0 AND pid='. (int)t3lib_div::_GET('id')
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			/**
			 * @var Tx_News2_Domain_Model_News
			 */
			$news = new Tx_News2_Domain_Model_News();

			$news->setImportId($row['uid']);
			$news->setPid($row['pid']);
			$news->setTitle($row['title']);
			$news->setCrdate($row['crdate']);
			$news->setCruserId($row['cruser_id']);
			$news->setEditlock($row['editlock']);
			$news->setAuthor($row['author']);
			$news->setAuthorEmail($row['author_email']);
			$news->setTitle($row['title']);
			$news->setBodytext($row['bodytext']);
			$news->setTeaser($row['short']);
			$news->setHidden($row['hidden']);
			$news->setDeleted($row['deleted']); // excluded via query
			$news->setStarttime($row['starttime']);
			$news->setEndtime($row['endtime']);
			$news->setDatetime($row['datetime']);
			$news->setArchive($row['archivedate']);
			$news->setFeGroup($row['fe_group']);
			$news->setKeywords($row['keywords']);
			$news->setSysLanguageUid($row['sys_language_uid']);
			$news->setL10nParent($row['l18n_parent']);
			$news->setType($row['type']);
			$news->setInternalurl($row['page']);
			$news->setExternalurl($row['ext_url']);

				// substitute l10n_parent first with old news record * -1
			$news->setL10nParent(($row['l10n_parent'] * -1));

			$news->setMedia($this->importNewsMediaCollection($row));
			$news->setRelatedFiles($this->importNewsFileCollection($row));
			$news->setRelatedLinks($this->importNewsLinkCollection($row));

			$newsRepository->add($news);
			
				//Enforce persistence which is the chance to get new uid
			$persistenceManager = t3lib_div::makeInstance('Tx_Extbase_Persistence_Manager');
			$persistenceManager->persistAll();

			$newUid = $news->getUid();
				
				// update news record
			$this->updateImportRecord('tt_news', $row['uid'], $newUid);
		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);
		
			// fix parent categories
		$fixedRecords = $this->fixNews(t3lib_div::_GET('id'));
	}
	
	/**
	 * Fix languages, related news and category relation
	 * 
	 * @param integer $pageUid page uid
	 */
	public function fixNews($pageUid) {
			// get all categories
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tt_news',
			'exportId > 0 AND pid='. (int)$pageUid
		);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				// l10n_parent
			$this->fixNewsL10nParentRelation($row);
				// related news
			$this->fixNewsRelatedRelation($row);
				// category
			$this->fixNewsCategoryRelation($row);
			
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'tt_news',
				'uid=' . $row['uid'],
				array(
					'exportid' => $row['exportid'] * (-1)
				)
			);
		}
	}


	public function importCategoryOverviewAction() {
		$recordCount = 0;

		try {
			$recordCount = $this->getRecordCount('tt_news_cat');
		} catch (Exception $e) {
			$this->flashMessageContainer->add($e->getMessage());
		}

		$this->view->assign('recordCount', $recordCount);
	}
	
	public function importCategoryAction() {
		$importCount = 0;
		
		/**
		 * @var Tx_News2_Domain_Repository_CategoryRepository
		 */
		$categoryRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_CategoryRepository');


		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tt_news_cat',
			'exportid = 0 AND deleted=0 AND pid='. (int)t3lib_div::_GET('id')
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			/**
			 * @var Tx_News2_Domain_Model_Category
			 */
			$category = new Tx_News2_Domain_Model_Category();

			$category->setImportId($row['uid']);
			$category->setPid($row['pid']);
			$category->setTitle($row['title']);
			$category->setStarttime($row['starttime']);
			$category->setEndtime($row['endtime']);
			$category->setCrdate($row['crdate']);
			$category->setSorting($row['sorting']);
			$category->setDescription($row['description']);
			$category->setSinglePid($row['single_pid']);
			$category->setShortcut($row['shortcut']);
			$category->setImage($this->copyCategoryImage($row));
			
			$categoryRepository->add($category);
			
				//Enforce persistence which is the chance to get new uid
			$persistenceManager = t3lib_div::makeInstance('Tx_Extbase_Persistence_Manager');
			$persistenceManager->persistAll();

			$newUid = $category->getUid();
			
				// update news record
			$this->updateImportRecord('tt_news_cat', $row['uid'], $newUid);
			$importCount++;
		}
		
		$this->view->assign('importedRecords', $importCount);

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		
			// fix parent categories
		$fixedRecords = $this->fixCategories(t3lib_div::_GET('id'));
		$this->view->assign('fixedRecords', $fixedRecords);
	}
}

?>
