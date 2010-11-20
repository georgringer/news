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
class Tx_News2_Controller_AbstractImportController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * Create media collection based on tt_news media record
	 *
	 * @param array $row tt_news record
	 * @return Tx_Extbase_Persistence_ObjectStorage
	 */
	public function importMediaCollection(array $row) {
		$mediaElementCollection = NULL;

			// import images into media elements
		$imageSplit = t3lib_div::trimExplode(',', $row['image'], TRUE);
		if (count($imageSplit) > 0) {
			$absolutePath = str_replace(
								'typo3/mod.php',
								'',
								t3lib_div::getIndPenv('SCRIPT_FILENAME')
					);

				// split addtional info, no trimming!
			$captionSplit = t3lib_div::trimExplode(chr(10), $row['imagecaption'], FALSE);
			$altSplit = t3lib_div::trimExplode(chr(10), $row['imagealttext'], FALSE);
			$titleSplit = t3lib_div::trimExplode(chr(10), $row['imagetitletext'], FALSE);

			$mediaElementCollection = new Tx_Extbase_Persistence_ObjectStorage();
			foreach($imageSplit as $key => $singleImage) {

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
	public function importFileCollection(array $row) {
		$fileElementCollection = NULL;

			// import images into media elements
		$fileSplit = t3lib_div::trimExplode(',', $row['news_files'], TRUE);
		if (count($fileSplit) > 0) {
			$absolutePath = str_replace(
					'typo3/mod.php',
					'',
					t3lib_div::getIndPenv('SCRIPT_FILENAME')
				);


			$fileElementCollection = new Tx_Extbase_Persistence_ObjectStorage();
			foreach($fileSplit as $key => $singleFile) {

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
	public function importLinkCollection(array $row) {
		$linkElementCollection = NULL;

			// import images into media elements
		$linkSplit = t3lib_div::trimExplode(chr(10), $row['links'], TRUE);
		if (count($linkSplit) > 0) {

			$linkElementCollection = new Tx_Extbase_Persistence_ObjectStorage();
			foreach($linkSplit as $key => $singleLink) {

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
