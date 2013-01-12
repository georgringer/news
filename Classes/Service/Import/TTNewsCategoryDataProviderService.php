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
 * tt_news category ImportService
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Service_Import_TTNewsCategoryDataProviderService implements Tx_News_Service_Import_DataProviderServiceInterface, t3lib_Singleton {

	protected $importSource = 'TT_NEWS_CATEGORY_IMPORT';

	/**
	 * Get total count of category records
	 *
	 * @return integer
	 */
	public function getTotalRecordCount() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)',
			'tt_news_cat',
			'deleted=0'
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
	public function getImportData($offset = 0, $limit = 200) {
		$importData = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',
			'tt_news_cat',
			'deleted=0',
			'',
			'',
			$offset . ',' . $limit
		);

		while ($row = mysql_fetch_array($res)) {
			$importData[] = array(
				'pid' => $row['pid'],
				'hidden' => $row['hidden'],
				'starttime' => $row['starttime'],
				'endtime'  => $row['endtime'],
				'title_lang_ol'  => $row['title_lang_ol'],
				'title'	=>	$row['title'],
				'description' => $row['description'],
				'image' => $row['image'],
				'shortcut' => $row['shortcut'],
				'single_pid' => $row['single_pid'],
				'parentcategory' => $row['parent_category'],
				'import_id' =>  $row['uid'],
				'import_source' => $this->importSource
			);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $importData;
	}
}
?>