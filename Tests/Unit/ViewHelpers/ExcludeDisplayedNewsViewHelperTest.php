<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\ViewHelpers\ExcludeDisplayedNewsViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;

/**
 * Tests for ExcludeDisplayedNewsViewHelper
 *
 */
class ExcludeDisplayedNewsViewHelperTest extends UnitTestCase
{

    /**
     * @test
     */
    public function newsIsAddedToExcludedList()
    {
        $viewHelper = new ExcludeDisplayedNewsViewHelper();
        $viewHelper->setRenderingContext($this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock());
        $this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], null);

        $newsItem1 = new News();
        $newsItem1->_setProperty('uid', '123');

        $viewHelper->setArguments(['newsItem' => $newsItem1]);
        $viewHelper->render();
        $this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], ['123' => '123']);

        $newsItem1 = new News();
        $newsItem1->_setProperty('uid', '123');
        $this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], ['123' => '123']);

        $newsItem2 = new News();
        $newsItem2->_setProperty('uid', '12');
        $viewHelper->setArguments(['newsItem' => $newsItem2]);
        $viewHelper->render();
        $this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], ['123' => '123', '12' => '12']);

        $newsItem3 = new News();
        $newsItem3->_setProperty('uid', '12');
        $newsItem3->_setProperty('_localizedUid', '456');
        $viewHelper->setArguments(['newsItem' => $newsItem3]);
        $viewHelper->render();
        $this->assertEquals($GLOBALS['EXT']['news']['alreadyDisplayed'], ['123' => '123', '12' => '12', '456' => '456']);
    }
}
