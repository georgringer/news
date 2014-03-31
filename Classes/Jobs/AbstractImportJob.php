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
 * Abstract Import job
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
abstract class Tx_News_Jobs_AbstractImportJob implements Tx_News_Jobs_ImportJobInterface {
	/**
	 * @var Tx_News_Service_Import_DataProviderServiceInterface
	 */
	protected $importDataProviderService;

	/**
	 * @var \TYPO3\CMS\Core\SingletonInterface
	 */
	protected $importService;

	/**
	 * @var array
	 */
	protected $importServiceSettings = array();

	/**
	 * @var array
	 */
	protected $importItemOverwrite = array();

	/*
	 * @var int
	 */
	protected $numberOfRecordsPerRun;

	/**
	 * @var int
	 */
	protected $increaseOffsetPerRunBy;

	/**
	 * Get number of runs
	 *
	 * @return integer
	 */
	public function getNumberOfRecordsPerRun() {
			// If not explicit defined by the job we import all records at once.
		if ($this->numberOfRecordsPerRun === NULL) {
			$this->numberOfRecordsPerRun = $this->importDataProviderService->getTotalRecordCount();
		}
		return $this->numberOfRecordsPerRun;
	}

	/**
	 * Checks if this job is enabled. Do perform some checks her if you need to.
	 * E.g. check if a certain extension is loaded or similar.
	 *
	 * @return bool
	 */
	public function isEnabled() {
		return TRUE;
	}

	/**
	 * Get job info.
	 *
	 * @return array
	 */
	public function getInfo() {
		$totalRecordCount = $this->importDataProviderService->getTotalRecordCount();
		$info = array(
			'totalRecordCount' => $totalRecordCount,
			'runsToComplete' => ceil($totalRecordCount / $this->getNumberOfRecordsPerRun()),
			'increaseOffsetPerRunBy' => $this->getNumberOfRecordsPerRun(),
		);

		return $info;
	}

	/**
	 * The actual run method.
	 *
	 * @param  int $offset
	 * @return void
	 */
	public function run($offset) {
		$importData = $this->importDataProviderService->getImportData($offset, $this->getNumberOfRecordsPerRun());
		$this->importService->import($importData, $this->importItemOverwrite, $this->importServiceSettings);
	}
}
