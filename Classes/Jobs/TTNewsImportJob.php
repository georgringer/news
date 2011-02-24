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
 * Import job
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */

class Tx_News2_Jobs_TTNewsImportJob implements Tx_News2_Jobs_ImportJobInterface {

	/**
	 * @var Tx_News2_Service_Import_TTNewsImportService
	 */
	protected $ttNewsImportService;

	/**
	 * @var Tx_News2_Domain_Service_NewsImportService
	 */
	protected $newsImportService;

	/**
	 * Inject ttNewsImportService
	 *
	 * @param Tx_News2_Service_Import_TTNewsImportService $ttNewsImportService
	 * @return void
	 */
	public function injectTtNewsImportService(Tx_News2_Service_Import_TTNewsImportService $ttNewsImportService) {
		$this->ttNewsImportService = $ttNewsImportService;
	}

	/**
	 * Inject newsImportService;
	 *
	 * @param Tx_News2_Domain_Service_NewsImportService $newsImportService
	 * @return void
	 */
	public function injectNewsImportService(Tx_News2_Domain_Service_NewsImportService $newsImportService) {
		$this->newsImportService= $newsImportService;
	}


	public function getInfo() {
		$totalRecordCount = $this->ttNewsImportService->getTotalRecordCount();
		return array(
			'totalRecordCount' => $totalRecordCount,
			'runsToComplete' => ceil($totalRecordCount / 30),
			'increaseOffsetPerRunBy' => 30,
		);
	}

	public function run($offset) {
		$importArray = $this->ttNewsImportService->createImportArray($offset, 30);
		$this->newsImportService->import($importArray);
	}
}
?>