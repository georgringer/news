<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Be;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\ViewHelpers\Be\IsCheckboxActiveViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for IsCheckboxActiveViewhelper
 *
 */
class IsCheckboxActiveViewhelperTest extends BaseTestCase
{
    public const OK_RESULT = 'checked="checked"';

    /**
     * @test
     *
     * @return void
     */
    public function activeCheckboxReturnsCorrectValue(): void
    {
        $viewHelper = new IsCheckboxActiveViewHelper();
        $viewHelper->setArguments([
            'id' => 10,
            'categories' => [5, 7, 10, 12]
        ]);
        $actualResult = $viewHelper->render();

        $this->assertEquals(self::OK_RESULT, $actualResult);
    }

    /**
     * @test
     *
     * @return void
     */
    public function nonActiveCheckboxReturnsNothing(): void
    {
        $viewHelper = new IsCheckboxActiveViewHelper();
        $viewHelper->setArguments([
            'id' => 8,
            'categories' => [5, 7, 10, 12]
        ]);
        $actualResult = $viewHelper->render();

        $this->assertEquals('', $actualResult);
    }

    /**
     * @test
     *
     * @return void
     */
    public function noCategoriesReturnNothing(): void
    {
        $viewHelper = new IsCheckboxActiveViewHelper();

        $this->assertEquals('', $viewHelper->render());
    }
}
