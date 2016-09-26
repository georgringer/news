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
use GeorgRinger\News\ViewHelpers\ExcludeDisplayedNewsViewHelper;

/**
 * Tests for ExcludeDisplayedNewsViewHelper
 *
 */
class ExcludeDisplayedNewsViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @test
     * @return void
     */
    public function newsIsAddedToExcludedList()
    {
        $viewHelper = new ExcludeDisplayedNewsViewHelper();
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
