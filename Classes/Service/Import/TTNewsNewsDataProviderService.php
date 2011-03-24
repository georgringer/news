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
 * tt_news ImportService
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News2_Service_Import_TTNewsNewsDataProviderService implements Tx_News2_Service_Import_DataProviderServiceInterface, t3lib_Singleton {

	protected $importSource = 'TT_NEWS_IMPORT';

	public function getTotalRecordCount() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)',
			'tt_news',
			'deleted=0 AND t3ver_id=0 AND t3ver_wsid = 0'
		);

		list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		return $count;
	}

	public function getImportData($offset = 0, $limit = 50) {
		$importData = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',
			'tt_news',
			'deleted=0 AND t3ver_id=0 AND t3ver_wsid = 0',
			'',
			'',
			$offset .',' . $limit
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$importData[] = array(
				'pid' => $row['pid'],
				'starttime' => $row['starttime'],
				'endtime'  => $row['endtime'],
				'title'	=>	$row['title'],
				'teaser' => $row['short'],
				'bodytext' => $row['bodytext'],
				'datetime' => $row['datetime'],
				'archive' => $row['archivedate'],
				'author' => $row['author'],
				'author_email' => $row['author_email'],
				'type' => $row['type'],
				'keywords' => $row['keywords'],
				'externalurl' => $row['ext_url'],
				'internalurl' => $row['page'],
				'categories' => $this->getCategories($row['uid']),
				'media' => $this->getMedia($row),
				'content_elements' => $row['tx_rgnewsce_ce'],
				'import_id' => $row['uid'],
				'import_source' => $this->importSource
			);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $importData;
	}

	protected function getCategories($newsUid) {
		$categories = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',
			'tt_news_cat_mm',
			'uid_local=' .$newsUid);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$categories[] = $row['uid_foreign'];
		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $categories;
	}

	protected function getMedia($row) {
		$media = array();

		if (empty($row['image'])) {
			return $media;
		}

		$images = t3lib_div::trimExplode(',', $row['image'], TRUE);
		$captions = t3lib_div::trimExplode(chr(10), $row['imagecaption'], FALSE);
		$alts = t3lib_div::trimExplode(chr(10), $row['imagealttext'], FALSE);
		$titles = t3lib_div::trimExplode(chr(10), $row['imagetitletext'], FALSE);

		$i = 0;
		foreach ($images as $image) {
			$media[] = array(
				'title' => $titles[$i],
				'alt' => $alts[$i],
				'caption' => $captions[$i],
				'image' => 'uploads/pics/' . $image,
				'type' => 0,
				'showinpreview' => (int)$i == 0
			);
			$i ++;
		}

		return $media;
	}

}
?>