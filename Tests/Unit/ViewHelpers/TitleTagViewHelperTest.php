<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\ViewHelpers\TitleTagViewHelper;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;

/**
 * Test for TitleTagViewHelper
 */
class TitleTagViewHelperTest extends UnitTestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|AccessibleMockObjectInterface
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
     */
    public function titleTagIsSet()
    {
        $title = 'Some title';
        /** @var TitleTagViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\TitleTagViewHelper', ['dummy']);
        $viewHelper::renderStatic([], function () {
            return 'Some title';
        }, $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock());
        $this->assertEquals($title, $GLOBALS['TSFE']->altPageTitle);
        $this->assertEquals($title, $GLOBALS['TSFE']->indexedDocTitle);
    }
}
