<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
 * Test class for Tx_News_Utility_ImportJob
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Utility_ImportJobTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
 	 * @test
	 */
	public function classCanBeRegistered() {
		$importJobInstance = new Tx_News_Utility_ImportJob();

		$jobs = array();
		$this->assertEquals($importJobInstance->getRegisteredJobs(), $jobs);

		// Add job #1
		$jobs[] = array(
			'className' => 'Class 1',
			'title' => 'Some title',
			'description' => ''
		);
		$importJobInstance->register('Class 1', 'Some title', '');
		$this->assertEquals($importJobInstance->getRegisteredJobs(), $jobs);

		// Add job #2
		$jobs[] = array(
			'className' => 'Class 2',
			'title' => '',
			'description' => 'Some description'
		);
		$importJobInstance->register('Class 2', '', 'Some description');
		$this->assertEquals($importJobInstance->getRegisteredJobs(), $jobs);
	}
}
