<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Be;

use GeorgRinger\News\ViewHelpers\Be\IsCheckboxActiveViewHelper;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for IsCheckboxActiveViewhelper
 */
class IsCheckboxActiveViewhelperTest extends BaseTestCase
{
    public const OK_RESULT = 'checked="checked"';

    #[Test]
    public function activeCheckboxReturnsCorrectValue(): void
    {
        $viewHelper = new IsCheckboxActiveViewHelper();
        $viewHelper->setArguments([
            'id' => 10,
            'categories' => [5, 7, 10, 12],
        ]);
        $actualResult = $viewHelper->render();

        self::assertEquals(self::OK_RESULT, $actualResult);
    }

    #[Test]
    public function nonActiveCheckboxReturnsNothing(): void
    {
        $viewHelper = new IsCheckboxActiveViewHelper();
        $viewHelper->setArguments([
            'id' => 8,
            'categories' => [5, 7, 10, 12],
        ]);
        $actualResult = $viewHelper->render();

        self::assertEquals('', $actualResult);
    }

    #[Test]
    public function noCategoriesReturnNothing(): void
    {
        $viewHelper = new IsCheckboxActiveViewHelper();

        self::assertEquals('', $viewHelper->render());
    }
}
