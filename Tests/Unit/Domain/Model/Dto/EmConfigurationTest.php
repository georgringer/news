<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

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
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;

/**
 * Tests for domains model News
 *
 */
class EmConfigurationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if the settings can be read
	 *
	 * @test
	 * @return void
	 */
	public function settingsCanBeRead() {
		$configuration = [
			'removeListActionFromFlexforms' => '2',
			'pageModuleFieldsNews' => 'test',
			'pageModuleFieldsCategory' => 'test',
			'tagPid' => 123,
			'prependAtCopy' => TRUE,
			'categoryRestriction' => 'fo',
			'contentElementRelation' => FALSE,
			'manualSorting' => FALSE,
			'archiveDate' => 'bar',
			'dateTimeNotRequired' => TRUE,
			'showImporter' => TRUE,
			'showAdministrationModule' => FALSE,
			'showMediaDescriptionField' => FALSE,
			'rteForTeaser' => FALSE,
			'storageUidImporter' => 1,
			'resourceFolderImporter' => 'fo',
		];

		$configurationInstance = new EmConfiguration($configuration);

		foreach ($configuration as $key => $value) {
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
		$configuration = [
			'removeListActionFromFlexforms' => 2,
			'pageModuleFieldsNews' => '',
			'pageModuleFieldsCategory' => '',
			'tagPid' => 0,
			'prependAtCopy' => TRUE,
			'categoryRestriction' => '',
			'contentElementRelation' => FALSE,
			'manualSorting' => FALSE,
			'archiveDate' => 'date',
			'dateTimeNotRequired' => false,
			'showImporter' => FALSE,
			'showAdministrationModule' => TRUE,
			'showMediaDescriptionField' => FALSE,
			'rteForTeaser' => FALSE,
			'storageUidImporter' => 1,
			'resourceFolderImporter' => '/news_import',
		];

		$configurationInstance = new EmConfiguration([]);

		foreach ($configuration as $key => $value) {
			$functionName = 'get' . ucwords($key);
			$this->assertEquals($value, $configurationInstance->$functionName());
		}
	}
}
