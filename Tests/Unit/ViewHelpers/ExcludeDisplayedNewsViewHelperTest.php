<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\ViewHelpers\ExcludeDisplayedNewsViewHelper;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for ExcludeDisplayedNewsViewHelper
 */
class ExcludeDisplayedNewsViewHelperTest extends BaseTestCase
{
    #[Test]
    #[IgnoreDeprecations]
    public function newsIsAddedToExcludedList(): void
    {
        $viewHelper = new ExcludeDisplayedNewsViewHelper();
        $viewHelper->setRenderingContext($this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock());

        $newsItem1 = new News();
        $newsItem1->_setProperty('uid', 123);

        $viewHelper->setArguments(['newsItem' => $newsItem1]);
        $viewHelper->render();
        self::assertEquals([123 => 123], $GLOBALS['EXT']['news']['alreadyDisplayed']);

        $newsItem1 = new News();
        $newsItem1->_setProperty('uid', 123);
        self::assertEquals([123 => 123], $GLOBALS['EXT']['news']['alreadyDisplayed']);

        $newsItem2 = new News();
        $newsItem2->_setProperty('uid', 12);
        $viewHelper->setArguments(['newsItem' => $newsItem2]);
        $viewHelper->render();
        self::assertEquals([123 => 123, 12 => 12], $GLOBALS['EXT']['news']['alreadyDisplayed']);

        $newsItem3 = new News();
        $newsItem3->_setProperty('uid', 12);
        $newsItem3->_setProperty('_localizedUid', 456);
        $viewHelper->setArguments(['newsItem' => $newsItem3]);
        $viewHelper->render();
        self::assertEquals([123 => 123, 12 => 12, 456 => 456], $GLOBALS['EXT']['news']['alreadyDisplayed']);
    }
}
