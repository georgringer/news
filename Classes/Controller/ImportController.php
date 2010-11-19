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
