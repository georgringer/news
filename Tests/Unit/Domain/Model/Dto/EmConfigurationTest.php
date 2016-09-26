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
class EmConfigurationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Test if the settings can be read
     *
     * @test
     * @return void
     */
    public function settingsCanBeRead()
    {
        $configuration = [
            'tagPid' => 123,
            'prependAtCopy' => true,
            'categoryRestriction' => 'fo',
            'contentElementRelation' => false,
            'manualSorting' => false,
            'archiveDate' => 'bar',
            'dateTimeNotRequired' => true,
            'showImporter' => true,
            'showAdministrationModule' => false,
            'rteForTeaser' => false,
            'storageUidImporter' => 1,
            'resourceFolderImporter' => 'fo',
            'hidePageTreeForAdministrationModule' => true,
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
    public function defaultSettingsCanBeRead()
    {
        $configuration = [
            'tagPid' => 0,
            'prependAtCopy' => true,
            'categoryRestriction' => '',
            'contentElementRelation' => false,
            'manualSorting' => false,
            'archiveDate' => 'date',
            'dateTimeNotRequired' => false,
            'showImporter' => false,
            'showAdministrationModule' => true,
            'rteForTeaser' => false,
            'storageUidImporter' => 1,
            'resourceFolderImporter' => '/news_import',
            'hidePageTreeForAdministrationModule' => false,
        ];

        $configurationInstance = new EmConfiguration([]);

        foreach ($configuration as $key => $value) {
            $functionName = 'get' . ucwords($key);
            $this->assertEquals($value, $configurationInstance->$functionName());
        }
    }
}
