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
 * Controller to import news records from tt_news2/cat
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Controller_AbstractImportController extends Tx_Extbase_MVC_Controller_ActionController {

	/***********************************
	 *   N E W S
	 *************************************/

	/**
	 * Create media collection based on tt_news media record
	 *
	 * @param array $row tt_news record
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function importNewsMediaCollection(array $row) {
		$mediaElementCollection = NULL;

			// import images into media elements
		$imageSplit = t3lib_div::trimExplode(',', $row['image'], TRUE);
		if (count($imageSplit) > 0) {
			$absolutePath = $this->getAbsPath();

				// split addtional info, no trimming!
			$captionSplit = t3lib_div::trimExplode(chr(10), $row['imagecaption'], FALSE);
			$altSplit = t3lib_div::trimExplode(chr(10), $row['imagealttext'], FALSE);
			$titleSplit = t3lib_div::trimExplode(chr(10), $row['imagetitletext'], FALSE);

			$mediaElementCollection = new Tx_Extbase_Persistence_ObjectStorage();
			foreach ($imageSplit as $key => $singleImage) {

					// copy file
				t3lib_div::upload_copy_move(
					$absolutePath . 'uploads/pics/' . $singleImage,
					$absolutePath . 'uploads/tx_news/' . $singleImage
				);

				/**
				 * @var Tx_News2_Domain_Model_Media
				 */
				$media = new Tx_News2_Domain_Model_Media();
				$media->setTstamp($row['tstamp']);
				$media->setCrdate($row['tstamp']);
				$media->setPid($row['pid']);
				$media->setType(0);
				$media->setCaption($captionSplit[$key]);
				$media->setAlt($altSplit[$key]);
				$media->setTitle($titleSplit[$key]);
				$media->setMedia($singleImage);
				$media->setSysLanguageUid($row['sys_language_uid']);

					// substitute l10n_parent first with old news record * -1
				$media->setL10nParent(($row['l10n_parent'] * -1));

					// show in preview enabled for 1st image
				if ($key == 0) {
					$media->setShowinpreview(1);
				}

				$mediaElementCollection->attach($media);
			}

		}

		return $mediaElementCollection;
	}


	/**
	 * Create file collection based on tt_news media record
	 *
	 * @param array $row tt_news record
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function importNewsFileCollection(array $row) {
		$fileElementCollection = NULL;

			// import images into media elements
		$fileSplit = t3lib_div::trimExplode(',', $row['news_files'], TRUE);
		if (count($fileSplit) > 0) {
			$absolutePath = $this->getAbsPath();


			$fileElementCollection = new Tx_Extbase_Persistence_ObjectStorage();
			foreach ($fileSplit as $key => $singleFile) {

					// copy file
				t3lib_div::upload_copy_move(
					$absolutePath . 'uploads/media/' . $singleFile,
					$absolutePath . 'uploads/tx_news/' . $singleFile
				);

				/**
				 * @var Tx_News2_Domain_Model_Media
				 */
				$file = new Tx_News2_Domain_Model_File();
				$file->setPid($row['pid']);
				$file->setTstamp($row['tstamp']);
				$file->setCrdate($row['tstamp']);
				$file->setFile($singleFile);

				$file->setSysLanguageUid($row['sys_language_uid']);

					// substitute l10n_parent first with old news record * -1
				$file->setL10nParent(($row['l10n_parent'] * -1));

				$fileElementCollection->attach($file);
			}

		}

		return $fileElementCollection;
	}

	/**
	 * Create link collection based on tt_news media record
	 *
	 * @param array $row tt_news record
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function importNewsLinkCollection(array $row) {
		$linkElementCollection = NULL;

			// import images into media elements
		$linkSplit = t3lib_div::trimExplode(chr(10), $row['links'], TRUE);
		if (count($linkSplit) > 0) {

			$linkElementCollection = new Tx_Extbase_Persistence_ObjectStorage();
			foreach ($linkSplit as $key => $singleLink) {

				/**
				 * @var Tx_News2_Domain_Model_Link
				 */
				$link = new Tx_News2_Domain_Model_Link();
				$link->setPid($row['pid']);
				$link->setTstamp($row['tstamp']);
				$link->setCrdate($row['tstamp']);

					// parse link. configuration: <LINK 123 _blank>foo</LINK>
				t3lib_div::print_array($singleLink);

					// remove the <LINK, </LINK> parts
				$rawLink = str_replace(
					array(
						'<LINK',
						'</LINK>'
					),
					'',
					$singleLink
				);

					// get link title
				$lastPos = strrpos($rawLink, '>');
				$title = substr($rawLink, $lastPos + 1);

					// get link attributes
				$linkAttributes = t3lib_div::trimExplode(' ', substr($rawLink, 0, $lastPos), TRUE);

				$link->setTitle($title);
				$link->setUri($linkAttributes[0]);
				$link->setSysLanguageUid($row['sys_language_uid']);

					// substitute l10n_parent first with old news record * -1
				$link->setL10nParent(($row['l10n_parent'] * -1));

				$linkElementCollection->attach($link);
			}

		}

		return $linkElementCollection;
	}

	public function fixNewsCategoryRelation(array $row) {
		if ($row['category'] > 0) {
			$mmRelations = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'tt_news_cat.*',
				'tt_news_cat_mm LEFT JOIN tt_news_cat ON tt_news_cat_mm.uid_foreign=tt_news_cat.uid',
				'tt_news_cat_mm.uid_local=' . $row['uid'],
				'',
				'tt_news_cat_mm.sorting'
			);

			$newCategoryList = array();
			foreach ($mmRelations as $relation) {
				if ($relation['exportid'] < 0) {
					$newCategoryList[] = $relation['exportid'] * (-1);
				} elseif($relation['exportid'] > 0) {
					$newCategoryList[] = $relation['exportid'];
				}
			}
			if (count($newCategoryList) > 0) {
				$catCount = 0;
				foreach ($newCategoryList as $newCat) {
					$GLOBALS['TYPO3_DB']->exec_INSERTquery(
						'tx_news2_domain_model_news_category_mm',
						array(
							'uid_local'	=> $row['exportid'],
							'uid_foreign' => $newCat,
							'sorting'	=> $catCount++
						)
					);
				}
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_news2_domain_model_news',
					'uid=' . $row['exportid'],
					array(
						'category' => $catCount
					)
				);
			}
		}

	}

	public function fixNewsRelatedRelation(array $row) {
		if ($row['related'] > 0) {
			$mmRelations = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'tt_news.*',
				'tt_news_related_mm LEFT JOIN tt_news ON tt_news_related_mm.uid_foreign=tt_news.uid',
				'tt_news_related_mm.uid_local=' . $row['uid'],
				'',
				'tt_news_related_mm.sorting'
			);

			$newRelatedList = array();
			foreach ($mmRelations as $relation) {
				if ($relation['exportid'] < 0) {
					$newRelatedList[] = $relation['exportid'] * (-1);
				} elseif($relation['exportid'] > 0) {
					$newRelatedList[] = $relation['exportid'];
				}
			}
			if (count($newRelatedList) > 0) {
				$relatedCount = 0;
				foreach ($newRelatedList as $newRelated) {
					$GLOBALS['TYPO3_DB']->exec_INSERTquery(
						'tx_news2_domain_model_news_related_mm',
						array(
							'uid_local'	=> $row['exportid'],
							'uid_foreign' => $newRelated,
							'sorting'	=> $newRelated++
						)
					);
				}
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_news2_domain_model_news',
					'uid=' . $row['exportid'],
					array(
						'related' => $newRelated
					)
				);
			}
		}

	}

	public function fixNewsL10nParentRelation(array $row) {
		if ($row['sys_language_uid'] > 0) {
			$updateArray = array();
			$updateArray['sys_language_uid'] = $row['sys_language_uid'];

				// language parent
			if ($row['l18n_parent'] > 0) {
				$languageParent = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
					'*',
					'tt_news',
					'uid=' . $row['l18n_parent']
				);
				$updateArray['l10n_parent'] = $languageParent['exportid'];
			}

				// update news2 record
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'tx_news2_domain_model_news',
				'uid=' . $row['exportid'],
				$updateArray
			);
		}
	}

	/***********************************
	 *    C A T E G O R Y
	 *************************************/

	public function copyCategoryImage(array $row) {
		$categoryImage = $row['image'];
		if (!empty($categoryImage)) {

			$absolutePath = $this->getAbsPath();

				// copy file
			t3lib_div::upload_copy_move(
				$absolutePath . 'uploads/pics/' . $categoryImage,
				$absolutePath . 'uploads/tx_news/' . $categoryImage
			);
		}

		return $categoryImage;
	}

	/**
	 * Update categories and fix parent category
	 *
	 * @param integer $pageUid page uid
	 * @return int count of modified records
	 */
	public function fixCategories($pageUid) {
		$count = 0;
			// get all categories
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tt_news_cat',
			'exportId > 0 AND pid='. (int)$pageUid
		);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

				// just need to check those records which got a parent
				// as this is the only field to update
			if ($row['parent_category'] > 0) {

					// get ttnews parent record to know its new pair
				$equivalentParentRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
					'*',
					'tt_news_cat',
					'uid=' . $row['parent_category']
				);

					// set parentcategory for news2
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_news2_domain_model_category',
					'uid=' . $row['exportid'],
					array(
						'parentcategory' => $equivalentParentRecord['exportid']
					)
				);
			}

				// update ttnewscat record to know that this recor is done
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'tt_news_cat',
				'uid=' . $row['uid'],
				array(
					'exportid' => $row['exportid'] * (-1)
				)
			);

			$count++;
		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $count;
	}




	/***********************************
	 *    G E N E R A L
	 *************************************/

	/**
	 * Get the absolute path
	 *
	 * @return string
	 */
	public function getAbsPath() {
		$absolutePath = str_replace(
			'typo3/mod.php',
			'',
			t3lib_div::getIndPenv('SCRIPT_FILENAME')
		);

		return $absolutePath;
	}

	/**
	 * Update tt_news record and set news2's id
	 *
	 * @param string $table tt_news or tt_news_cat
	 * @param integer $uid uid of tt_news record
	 * @param integer $newUid uid of news2' record
	 * @return void
	 */
	public function updateImportRecord($table, $uid, $newUid) {
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			$table,
			'uid=' . (int)$uid,
			array(
				'exportid' => $newUid,

			)
		);
	}

	/**
	 * Get count of records
	 * @param string $table tablename
	 * @return integer record count
	 */
	public function getRecordCount($table = 'tt_news') {
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