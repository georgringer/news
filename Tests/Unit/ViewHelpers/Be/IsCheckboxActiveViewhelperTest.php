<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Be;

/*
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
use GeorgRinger\News\ViewHelpers\Be\IsCheckboxActiveViewHelper;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Tests for IsCheckboxActiveViewhelper
 *
 */
class IsCheckboxActiveViewhelperTest extends UnitTestCase
{

    const OK_RESULT = 'checked="checked"';

    /**
     * @test
     */
    public function activeCheckboxReturnsCorrectValue()
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
     */
    public function nonActiveCheckboxReturnsNothing()
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
     */
    public function noCategoriesReturnNothing()
    {
        $viewHelper = new IsCheckboxActiveViewHelper();

        $this->assertEquals('', $actualResult);
    }
}
