<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Format;

use GeorgRinger\News\ViewHelpers\Format\NothingViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for NothingViewHelper
 */
class NothingViewHelperTest extends BaseTestCase
{
    /**
     * Test of nothing viewHelper
     *
     * @test
     */
    public function noResultExpected(): void
    {
        $viewHelper = $this->getAccessibleMock(NothingViewHelper::class, ['renderChildren']);
        $viewHelper->expects(self::once())->method('renderChildren')->willReturn('whatever content');
        $actualResult = $viewHelper->render();
        self::assertNull($actualResult);
    }
}
