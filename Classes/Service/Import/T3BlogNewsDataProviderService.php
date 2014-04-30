<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Ingo Renner <ingo@typo3.org>
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
 * t3blog ImportService
 *
 * @package TYPO3
 * @author Ingo Renner <ingo@typo3.org>
 */
class Tx_News_Service_Import_T3BlogNewsDataProviderService implements Tx_News_Service_Import_DataProviderServiceInterface, \TYPO3\CMS\Core\SingletonInterface {

	protected $importSource = 'TX_T3BLOG_IMPORT';

	/**
	 * Get total record count
	 *
	 * @return integer
	 */
	public function getTotalRecordCount() {
		return $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
			'uid',
			'tx_t3blog_post',
			'deleted=0 AND t3ver_id=0 AND t3ver_wsid = 0'
		);
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

		$posts = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'post.*, author.realName AS author_name, author.email AS author_email',
			'tx_t3blog_post AS post LEFT JOIN be_users AS author ON post.author = author.uid',
			'post.deleted=0 AND post.t3ver_id=0 AND post.t3ver_wsid = 0',
			'',
			'',
			$offset . ',' . $limit
		);

		foreach ($posts as $row) {
			$importData[] = array(
				'pid'              => $row['pid'],
				'crdate'           => $row['crdate'],
				'tstamp'           => $row['tstamp'],
				'cruser_id'        => $row['cruser_id'],
				'hidden'           => $row['hidden'],
				'l10n_parent'      => $row['l18n_parent'],
				'sys_language_uid' => $row['sys_language_uid'],
				'starttime'        => $row['starttime'],
				'endtime'          => $row['endtime'],
				'title'            => $row['title'],
				'datetime'         => $row['date'],
				'keywords'         => $row['tagClouds'],
				'author'           => $row['author_name'] ?: '',
				'author_email'     => $row['author_email'] ?: '',
				'categories'       => $this->getCategories($row['uid']),
				'content_elements' => $this->getContentElements($row['uid']),
				'import_id'        => $row['uid'],
				'import_source'    => $this->importSource
			);
		}

		return $importData;
	}

	/**
	 * Provides an array of category uids for a news record
	 *
	 * @param integer $postUid news uid
	 * @return array
	 */
	protected function getCategories($postUid) {
		$categories = array();

		$categoryRows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'uid_foreign',
			'tx_t3blog_post_cat_mm',
			'uid_local = ' . $postUid
		);

		foreach ($categoryRows as $category) {
			$categories[] = $category['uid_foreign'];
		}

		return $categories;
	}

	/**
	 * Gets the content elements associated to a blog post
	 *
	 * @param int $postUid blog post uid
	 * @return array content element uids associated to the blog post
	 */
	protected function getContentElements($postUid) {
		$contentElements = array();

		$contentElementRows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'uid',
			'tt_content',
			'irre_parentid = ' . $postUid
		);

		foreach ($contentElementRows as $contentElement) {
			$contentElements[] = $contentElement['uid'];
		}

		return implode(',', $contentElements);
	}

}
