<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\News;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for IfIsActiveViewHelper
 */
class IfIsActiveViewHelperTest extends UnitTestCase
{

    /**
     * @var \GeorgRinger\News\ViewHelpers\IfIsActiveViewHelper
     */
    protected $viewHelper;

    public function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\IfIsActiveViewHelper', ['renderThenChild', 'renderElseChild']);
    }

    /**
     * @test
     */
    public function elseChildIsCalledWithNoGetArguments()
    {
        $newsItem = new News();
        $newsItem->_setProperty('uid', 123);

        $this->viewHelper->expects($this->once())
            ->method('renderElseChild');

        $this->viewHelper->render($newsItem);
    }

    /**
     * @test
     */
    public function elseChildIsCalledWithWrongGetArguments()
    {
        $_GET['tx_news_pi1']['news'] = 456;
        $newsItem = new News();
        $newsItem->_setProperty('uid', 123);

        $this->viewHelper->expects($this->once())
            ->method('renderElseChild');

        $this->viewHelper->render($newsItem);
    }
}
