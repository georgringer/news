<?php

namespace GeorgRinger\News\Tests\Unit\TreeProvider;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for DatabaseTreeDataProvider
 */
class DatabaseTreeDataProviderTest extends UnitTestCase
{

    /**
     * @var array
     */
    private $backupGlobalVariables;

    public function setUp()
    {
        $this->backupGlobalVariables = [
            'BE_USER' => $GLOBALS['BE_USER'],
        ];
    }

    public function tearDown()
    {
        foreach ($this->backupGlobalVariables as $key => $data) {
            $GLOBALS[$key] = $data;
        }
        unset($this->backupGlobalVariables);
    }

    /**
     * @test
     */
    public function canSingleCategoryAclSettingBeActivated()
    {
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\TreeProvider\\DatabaseTreeDataProvider', ['dummy'], [], '',
            $callOriginalConstructor = false);

        $this->assertEquals(false, $mockTemplateParser->_call('isSingleCategoryAclActivated'));

        // Add TsConfig array
        $GLOBALS['BE_USER']->userTS['tx_news.'] = [];
        $this->assertEquals(false, $mockTemplateParser->_call('isSingleCategoryAclActivated'));

        // Set the access
        $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] = '1';
        $this->assertEquals(true, $mockTemplateParser->_call('isSingleCategoryAclActivated'));

        // Remove access again
        $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] = '0';
        $this->assertEquals(false, $mockTemplateParser->_call('isSingleCategoryAclActivated'));
    }
}
