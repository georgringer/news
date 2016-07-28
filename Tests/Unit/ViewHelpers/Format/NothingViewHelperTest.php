<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Format;

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

/**
 * Tests for NothingViewHelper
 *
 */
class NothingViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Test of nothing viewHelper
     *
     * @test
     * @return void
     */
    public function noResultExpected()
    {
        $viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\Format\\NothingViewHelper', ['renderChildren']);
        $viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('whatever content'));
        $actualResult = $viewHelper->render();
        $this->assertEquals(null, $actualResult);
    }
}
