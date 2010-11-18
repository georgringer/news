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

	public function indexAction() {
		$check = $this->checkForInstalledTtnews();

		$this->view->assign('check', $check);
		if ($check) {
				// proceed
		}
	}
	
	
	/**
	 * Check if tt_news is installed and if there are records for current page
	 * @return boolean 
	 */
	private function checkForInstalledTtnews() {
		$proceedWithImport = TRUE;
		if (!t3lib_extMgm::isLoaded('tt_news')) {
			$this->flashMessages->add('tt_news is not installed.');
			
			$proceedWithImport = FALSE;
		} else {
			$recordCount = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
				'uid',
				'tt_news',
				'deleted=0 AND pid=' . $this->id
			);
		
			if ($recordCount == 0) {
				$this->flashMessages->add('no records found for this page.');
				$proceedWithImport = FALSE;
			}
		}
		
		return $proceedWithImport;
	}
	
}

?>
