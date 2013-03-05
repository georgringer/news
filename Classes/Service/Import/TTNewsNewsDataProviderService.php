<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
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
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Service_Import_TTNewsNewsDataProviderService implements Tx_News_Service_Import_DataProviderServiceInterface, t3lib_Singleton {

	protected $importSource = 'TT_NEWS_IMPORT';

	/**
	 * Get total record count
	 *
	 * @return integer
	 */
	public function getTotalRecordCount() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)',
			'tt_news',
			'deleted=0 AND t3ver_id=0 AND t3ver_wsid = 0'
		);

		list($count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return (int)$count;
	}

	/**
	 * Get the partial import data, based on offset + limit
	 *
	 * @param integer $offset offset
	 * @param integer $limit limit
	 * @return array
	 */
	public function getImportData($offset = 0, $limit = 50) {
		$importData = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',
			'tt_news',
			'deleted=0 AND t3ver_id=0 AND t3ver_wsid = 0',
			'',
			'',
			$offset . ',' . $limit
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$importData[] = array(
				'pid' => $row['pid'],
				'hidden' => $row['hidden'],
				'l10n_parent' => $row['l18n_parent'],
				'sys_language_uid' => $row['sys_language_uid'],
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
				'related_files' => $this->getFiles($row),
				'related_links' => $this->getRelatedLinks($row['links']),
				'content_elements' => $row['tx_rgnewsce_ce'],
				'import_id' => $row['uid'],
				'import_source' => $this->importSource
			);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $importData;
	}

	/**
	 * Parses the related files
	 *
	 * @param array $row
	 * @return array
	 */
	protected function getFiles(array $row) {
		if (empty($row['news_files'])) {
			return FALSE;
		}

		$relatedFiles = array();

		$files = t3lib_div::trimExplode(',', $row['news_files']);

		foreach ($files as $file) {
			$relatedFiles[] = array(
				'file' => 'uploads/media/' . $file
			);
		}

		return $relatedFiles;
	}

	/**
	 * Get correct categories of a news record
	 *
	 * @param integer $newsUid news uid
	 * @return array
	 */
	protected function getCategories($newsUid) {
		$categories = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',
			'tt_news_cat_mm',
			'uid_local=' . $newsUid);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$categories[] = $row['uid_foreign'];
		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $categories;
	}

	/**
	 * Get correct media elements to be imported
	 *
	 * @param array $row news record
	 * @return array
	 */
	protected function getMedia(array $row) {
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

	/**
	 * Get link elements to be imported
	 *
	 * @param string $newsLinks
	 * @return array
	 */
	protected function getRelatedLinks($newsLinks) {
		$links = array();

		if (empty($newsLinks)) {
			return $links;
		}

		$newsLinks = str_replace(array('<link ', '</link>'), array('<LINK ', '</LINK>'), $newsLinks);

		$linkList = t3lib_div::trimExplode('</LINK>', $newsLinks, TRUE);
		foreach ($linkList as $singleLink) {
			if (strpos($singleLink, '<LINK') === FALSE) {
				continue;
			}
			$title = substr(strrchr($singleLink, '>'), 1);
			$uri = str_replace('>' . $title, '', substr(strrchr($singleLink, '<link '), 6));
			$links[] = array(
				'uri' => $uri,
				'title' => $title,
				'description' => '',
			);
		}
		return $links;
	}

}

?>