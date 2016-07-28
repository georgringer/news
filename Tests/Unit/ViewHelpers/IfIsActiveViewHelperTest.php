<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

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
use GeorgRinger\News\Domain\Model\News;

/**
 * Tests for IfIsActiveViewHelper
 */
class IfIsActiveViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
     * @return void
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
     * @return void
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
