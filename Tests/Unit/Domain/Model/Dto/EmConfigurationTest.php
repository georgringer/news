<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Tests for domains model News
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Tests_Unit_Domain_Model_Dto_EmConfigurationTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * Test if the settings can be read
	 *
	 * @test
	 * @return void
	 */
	public function settingsCanBeRead() {
		$configuration = array(
			'removeListActionFromFlexforms' => '1',
			'removeListActionFromFlexforms' => '2',
			'pageModuleFieldsNews' => 'test',
			'pageModuleFieldsCategory' => 'test',
			'tagPid' => 123,
			'prependAtCopy' => TRUE,
			'categoryRestriction' => 'fo',
			'contentElementRelation' => FALSE,
			'manualSorting' => FALSE,
			'archiveDate' => 'bar',
			'showImporter' => TRUE,
		);

		$configurationInstance = new Tx_News_Domain_Model_Dto_EmConfiguration($configuration);

		foreach($configuration as $key => $value) {
			$functionName = 'get' . ucwords($key);
			$this->assertEquals($value, $configurationInstance->$functionName());
		}
	}

	/**
	 * Test if default settings can be read
	 *
	 * @test
	 * @return void
	 */
	public function defaultSettingsCanBeRead() {
		$configuration = array(
			'removeListActionFromFlexforms' => '2',
			'removeListActionFromFlexforms' => 2,
			'pageModuleFieldsNews' => '',
			'pageModuleFieldsCategory' => '',
			'tagPid' => 0,
			'prependAtCopy' => TRUE,
			'categoryRestriction' => '',
			'contentElementRelation' => FALSE,
			'manualSorting' => FALSE,
			'archiveDate' => 'date',
			'showImporter' => FALSE,
		);

		$configurationInstance = new Tx_News_Domain_Model_Dto_EmConfiguration(array());

		foreach($configuration as $key => $value) {
			$functionName = 'get' . ucwords($key);
			$this->assertEquals($value, $configurationInstance->$functionName());
		}
	}
}

?>