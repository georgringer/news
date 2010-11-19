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
class Tx_News2_Controller_ImportController extends Tx_Extbase_MVC_Controller_ActionController {
	var $currentPageId = NULL;

	
	public function indexAction() {
		$recordCount = 0;
		
		try {
			$recordCount = $this->getRecordCount('tt_news');
		} catch (Exception $e) {
			$this->flashMessages->add($e->getMessage());
		}

		$this->view->assign('recordCount', $recordCount);	
	}
	
	public function importNewsAction() {
		/**
		 * @var Tx_News2_Domain_Repository_NewsRepository
		 */
		$newsRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_NewsRepository');
		/**
		 * @var Tx_News2_Domain_Repository_MediaRepository
		 */
		$mediaRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_MediaRepository');
		
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tt_news',
			'deleted=0 AND pid='. (int)t3lib_div::_GET('id')
		);
		
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			/**
			 * @var Tx_News2_Domain_Model_News
			 */
			$news = new Tx_News2_Domain_Model_News();

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
			$news->setFeGroup($row['fe_group']);
			
				// @todo how to import related news?
				// idea: import tt_news ORDER BY uid ASC to ensure news imported before?
			
				// import images into media elements
			$imageSplit = t3lib_div::trimExplode(',', $row['image'], TRUE);
			if (count($imageSplit) > 0) {
					// split addtional info, no trimming!
				$captionSplit = t3lib_div::trimExplode(',', $row['imagecaption'], FALSE);
				$altSplit = t3lib_div::trimExplode(',', $row['imagealttext'], FALSE);
				$titleSplit = t3lib_div::trimExplode(',', $row['imagetitletext'], FALSE);
				
				$mediaElementCollection = new Tx_Extbase_Persistence_ObjectStorage();
				foreach($imageSplit as $key => $singleImage) {
					
					/**
					 * @var Tx_News2_Domain_Model_Media
					 */
					$media = new Tx_News2_Domain_Model_Media();
					$media->setTstamp($row['tstamp']);
					$media->setCrdate($row['tstamp']);
					$media->setPid($row['pid']);
					$media->setType(0);
					$media->setCaption($captionSplit[0]);
					$media->setAlt($altSplit[0]);
					$media->setTitle($titleSplit[0]);
					$media->setMedia($singleImage);

						// show in preview enabled for 1st image
					if ($key == 0) {
						$media->setShowinpreview(1);
					}
					
					$mediaElementCollection->attach($media);
				}
				$news->setMedia($mediaElementCollection);
			}
			

//  related int(11) DEFAULT '0' NOT NULL,


//  category int(11) DEFAULT '0' NOT NULL,
//
//  news_files text,
//  links text,
//  type tinyint(4) DEFAULT '0' NOT NULL,
//  page int(11) DEFAULT '0' NOT NULL,
//  keywords text,
//  archivedate int(11) DEFAULT '0' NOT NULL,
//  ext_url varchar(255) DEFAULT '' NOT NULL,
//  
//  sys_language_uid int(11) DEFAULT '0' NOT NULL,
//  l18n_parent int(11) DEFAULT '0' NOT NULL,
//  l18n_diffsource mediumblob NOT NULL,
//  no_auto_pb tinyint(4) unsigned DEFAULT '0' NOT NULL,
//
//  t3ver_oid int(11) DEFAULT '0' NOT NULL,
//  t3ver_id int(11) DEFAULT '0' NOT NULL,
//  t3ver_wsid int(11) DEFAULT '0' NOT NULL,
//  t3ver_label varchar(30) DEFAULT '' NOT NULL,
//  t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
//  t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
//  t3ver_count int(11) DEFAULT '0' NOT NULL,
//  t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
//  t3_origuid int(11) DEFAULT '0' NOT NULL,		
			
			$newsRepository->add($news);
	
		}
		
	}
	
	/**
	 * Get count of records
	 * @param string $table tablename
	 * @return integer record count 
	 */
	private function getRecordCount($table = 'tt_news') {
		$recordCount = 0;
		
		$this->currentPageId = (int)t3lib_div::_GET('id');
		
			// check if there is a page id
		if ($this->currentPageId === 0) {	
			throw new Exception('no id');
		} 
		
			// check if tt_news is even installed
		if (!t3lib_extMgm::isLoaded('tt_news')) {
			throw new Exception('tt_news is not installed');
		}
		
			// get record count
		$recordCount = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
			'uid',
			$table,
			'deleted=0 AND pid=' . $this->currentPageId
		);

		if ($recordCount == 0) {
			throw new Exception('no records found for this page.');
		}
		
		return $recordCount;
	}
	
}

?>
