<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Utility\EmConfiguration;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test for EmConfiguration
 *
 */
class EmConfigurationTest extends UnitTestCase
{

    /**
     * @var array
     */
    private $backupGlobalVariables;

    public function setUp()
    {
        $this->backupGlobalVariables = [
            'TYPO3_CONF_VARS' => $GLOBALS['TYPO3_CONF_VARS'],
        ];
        $GLOBALS['TCA'][$this->testTableName] = ['ctrl' => []];
        $GLOBALS[$this->testGlobalNamespace] = [];
    }

    public function tearDown()
    {
        foreach ($this->backupGlobalVariables as $key => $data) {
            $GLOBALS[$key] = $data;
        }
        unset($this->backupGlobalVariables);
    }

    /**
     * Test if parse settings returns the settings
     *
     * @test
     * @dataProvider settingsAreCorrectlyReturnedDataProvider
     */
    public function settingsAreCorrectlyReturned($expectedFields, $expected)
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news'] = $expectedFields;
        $this->assertEquals($expected, EmConfiguration::parseSettings());
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function settingsAreCorrectlyReturnedDataProvider()
    {
        return [
            'noConfigurationFound' => [
                null, []
            ],
            'wrongConfigurationFound' => [
                serialize('Test 123'), []
            ],
            'workingConfiguration' => [
                serialize(['key' => 'value']), ['key' => 'value']
            ],
        ];
    }

    /**
     * Test if configuration model is correctly returned
     *
     * @test
     * @dataProvider extensionManagerConfigurationIsCorrectlyReturnedDataProvider
     */
    public function extensionManagerConfigurationIsCorrectlyReturned($expectedFields, $expected)
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news'] = $expectedFields;
        $this->assertEquals($expected, EmConfiguration::getSettings());
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function extensionManagerConfigurationIsCorrectlyReturnedDataProvider()
    {
        return [
            'noConfigurationFound' => [
                null, new \GeorgRinger\News\Domain\Model\Dto\EmConfiguration([])
            ],
            'wrongConfigurationFound' => [
                serialize('Test 123'), new \GeorgRinger\News\Domain\Model\Dto\EmConfiguration([])
            ],
            'noValidPropertiesFound' => [
                serialize(['key' => 'value']), new \GeorgRinger\News\Domain\Model\Dto\EmConfiguration([])
            ],
            'validPropertiesFound' => [
                serialize(['key' => 'value', 'resourceFolderImporter' => 'test']), new \GeorgRinger\News\Domain\Model\Dto\EmConfiguration(['resourceFolderImporter' => 'test'])
            ],
        ];
    }
}
