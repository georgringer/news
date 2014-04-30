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
 * t3blog category ImportService
 *
 * @package TYPO3
 * @author Ingo Renner <ingo@typo3.org>
 */
class Tx_News_Service_Import_T3BlogCategoryDataProviderService implements Tx_News_Service_Import_DataProviderServiceInterface, \TYPO3\CMS\Core\SingletonInterface {

	protected $importSource = 'TX_T3BLOG_CATEGORY_IMPORT';

	/**
	 * Get total count of category records
	 *
	 * @return integer
	 */
	public function getTotalRecordCount() {
		return $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
			'uid',
			'tx_t3blog_cat',
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
	public function getImportData($offset = 0, $limit = 200) {
		$importData = array();

		$categories = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'tx_t3blog_cat',
			'deleted=0',
			'',
			'',
			$offset . ',' . $limit
		);

		foreach ($categories as $category) {
			$importData[] = array(
				'pid'            => $category['pid'],
				'hidden'         => $category['hidden'],
				'starttime'      => $category['starttime'],
				'endtime'        => $category['endtime'],
				'tstamp'         => $category['tstamp'],
				'crdate'         => $category['crdate'],
				'title'          => $category['catname'],
				'description'    => $category['description'],
				'parentcategory' => $category['parent_id'],
				'import_id'      => $category['uid'],
				'import_source'  => $this->importSource
			);
		}

		return $importData;
	}
}
