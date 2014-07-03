<?php
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

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
