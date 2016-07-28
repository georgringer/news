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
use GeorgRinger\News\ViewHelpers\TitleTagViewHelper;

/**
 * Test for TitleTagViewHelper
 */
class TitleTagViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $tsfe = null;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->tsfe = $this->getAccessibleMock('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', ['dummy'], [], '', false);
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * Test of strip tags viewhelper
     *
     * @test
     * @return void
     */
    public function titleTagIsSet()
    {
        $title = 'Some title';
        /** @var TitleTagViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\TitleTagViewHelper', ['renderChildren']);
        $viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue($title));

        $viewHelper->render();
        $this->assertEquals($title, $GLOBALS['TSFE']->altPageTitle);
        $this->assertEquals($title, $GLOBALS['TSFE']->indexedDocTitle);
    }
}
