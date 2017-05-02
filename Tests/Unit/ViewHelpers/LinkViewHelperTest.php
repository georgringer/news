<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Service\SettingsService;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test for LinkViewHelper
 */
class LinkViewHelperTest extends UnitTestCase
{
    protected $mockedContentObjectRenderer;

    protected $mockedViewHelper;

    /** @var News */
    protected $newsItem;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->newsItem = new News();

        $this->mockedViewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\LinkViewHelper', ['init', 'renderChildren']);
        $this->mockedContentObjectRenderer = $this->getAccessibleMock('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer', ['typoLink_URL', 'typoLink']);
        $pluginSettings = $this->getAccessibleMock('GeorgRinger\\News\\Service\\SettingsService', ['getSettings']);
        $tag = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\TagBuilder', ['addAttribute', 'setContent', 'render']);
        $this->mockedViewHelper->_set('cObj', $this->mockedContentObjectRenderer);
        $this->mockedViewHelper->_set('tag', $tag);
        $this->mockedViewHelper->_set('pluginSettingsService', $pluginSettings);
        $this->mockedViewHelper->_set('arguments', ['newsItem' => $this->newsItem]);
    }

    /**
     * @test
     */
    public function internalPageIsUsed()
    {
        $url = '123';
        $result = ['parameter' => $url];

        $this->mockedContentObjectRenderer->expects($this->once())->method('typoLink_URL')->with($result);

        $this->newsItem->setType(1);
        $this->newsItem->setInternalurl($url);

        $this->mockedViewHelper->render();
    }

    /**
     * @test
     */
    public function externalUrlIsUsed()
    {
        $url = 'http://www.typo3.org';
        $result = ['parameter' => $url];

        $this->mockedContentObjectRenderer->expects($this->once())->method('typoLink_URL')->with($result);

        $this->newsItem->setType(2);
        $this->newsItem->setExternalurl($url);

        $this->mockedViewHelper->render();
    }

    /**
     * @test
     */
    public function humanReadAbleDateIsAddedToConfiguration()
    {
        $dateTime = new \DateTime('2014-05-16');
        $newsItem = new \GeorgRinger\News\Domain\Model\News();
        $newsItem->_setProperty('uid', 123);
        $newsItem->setDatetime($dateTime);

        $tsSettings = [
            'link' => [
                'hrDate' => [
                    '_typoScriptNodeValue' => 1,
                    'day' => 'j',
                    'month' => 'n',
                    'year' => 'Y',
                ]
            ]
        ];
        $configuration = [];
        $expected = '&tx_news_pi1[news]=123&tx_news_pi1[controller]=News&tx_news_pi1[action]=detail&tx_news_pi1[day]=16&tx_news_pi1[month]=5&tx_news_pi1[year]=2014';

        $result = $this->mockedViewHelper->_call('getLinkToNewsItem', $newsItem, $tsSettings, $configuration);
        $this->assertEquals($expected, $result['additionalParams']);
    }

    /**
     * @test
     */
    public function controllerAndActionAreSkippedInUrl()
    {
        $newsItem = new \GeorgRinger\News\Domain\Model\News();
        $newsItem->_setProperty('uid', 123);

        $tsSettings = [
            'link' => [
                'skipControllerAndAction' => 1
            ]
        ];
        $configuration = [];
        $expected = '&tx_news_pi1[news]=123';

        $result = $this->mockedViewHelper->_call('getLinkToNewsItem', $newsItem, $tsSettings, $configuration);
        $this->assertEquals($expected, $result['additionalParams']);
    }

    /**
     * @test
     */
    public function getDetailPidFromCategoriesReturnsCorrectValue()
    {
        $viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\LinkViewHelper', ['dummy']);

        $newsItem = new \GeorgRinger\News\Domain\Model\News();

        $categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $category1 = new Category();
        $categories->attach($category1);

        $category2 = new Category();
        $category2->setSinglePid('123');
        $categories->attach($category2);

        $category3 = new Category();
        $category3->setSinglePid('456');
        $categories->attach($category3);

        $newsItem->setCategories($categories);

        $result = $viewHelper->_call('getDetailPidFromCategories', [], $newsItem);
        $this->assertEquals(123, $result);
    }

    /**
     * @test
     * @dataProvider getDetailPidFromDefaultDetailPidReturnsCorrectValueDataProvider
     */
    public function getDetailPidFromDefaultDetailPidReturnsCorrectValue($settings, $expected)
    {
        $viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\LinkViewHelper', ['dummy']);

        $result = $viewHelper->_call('getDetailPidFromDefaultDetailPid', $settings, null);
        $this->assertEquals($expected, $result);
    }

    public function getDetailPidFromDefaultDetailPidReturnsCorrectValueDataProvider()
    {
        return [
            [null, 0],
            [[], 0],
            [['defaultDetailPid' => '789'], 789],
            [['defaultDetailPid' => '45xy'], 45],
        ];
    }

    /**
     * @test
     * @dataProvider getDetailPidFromFlexformReturnsCorrectValueDataProvider
     */
    public function getDetailPidFromFlexformReturnsCorrectValue($settings, $expected)
    {
        $viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\LinkViewHelper', ['dummy']);

        $result = $viewHelper->_call('getDetailPidFromFlexform', $settings, null);
        $this->assertEquals($expected, $result);
    }

    public function getDetailPidFromFlexformReturnsCorrectValueDataProvider()
    {
        return [
            [null, 0],
            [[], 0],
            [['detailPid' => '123'], 123],
            [['detailPid' => '456xy'], 456],
        ];
    }

    /**
     * @test
     */
    public function noNewsReturnsChildren()
    {
        $settingService = $this->getAccessibleMock(SettingsService::class, ['getConfiguration', 'getSettings']);
        $viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\LinkViewHelper', ['renderChildren', 'getSettings']);
        $viewHelper->_set('pluginSettingsService', $settingService);
        $result = $viewHelper->_call('render');
        $this->assertEquals('', $result);
    }
}
