<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for domains model News
 *
 */
class EmConfigurationTest extends BaseTestCase
{

    /**
     * Test if the settings can be read
     *
     * @test
     *
     * @return void
     */
    public function settingsCanBeRead(): void
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
            'slugBehaviour' => 'uniqueInSite',
        ];

        $configurationInstance = $this->getAccessibleMock(EmConfiguration::class, ['dummy'], [], '', false);
        foreach ($configuration as $key => $value) {
            $configurationInstance->_set($key, $value);
        }
        foreach ($configuration as $key => $value) {
            $functionName = 'get' . ucwords($key);
            $this->assertEquals($value, $configurationInstance->$functionName());
        }
    }

    /**
     * Test if default settings can be read
     *
     * @test
     *
     * @return void
     */
    public function defaultSettingsCanBeRead(): void
    {
        $configuration = [
            'tagPid' => 0,
            'prependAtCopy' => true,
            'categoryRestriction' => '',
            'contentElementRelation' => true,
            'manualSorting' => false,
            'archiveDate' => 'date',
            'dateTimeNotRequired' => false,
            'showImporter' => false,
            'showAdministrationModule' => true,
            'rteForTeaser' => false,
            'storageUidImporter' => 1,
            'resourceFolderImporter' => '/news_import',
            'hidePageTreeForAdministrationModule' => false,
            'slugBehaviour' => 'unique',
        ];

        $configurationInstance = $this->getAccessibleMock(EmConfiguration::class, ['dummy'], [], '', false);

        foreach ($configuration as $key => $value) {
            $functionName = 'get' . ucwords($key);
            $this->assertEquals($value, $configurationInstance->$functionName());
        }
    }
}
