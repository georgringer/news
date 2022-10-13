<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Utility\TemplateLayout;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * TemplateLayout utility class unit tests
 */
class TemplateLayoutTest extends BaseTestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function templatesFoundInTypo3ConfVars(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'] = [
            0 => [
                0 => 'Layout 1',
                1 => 'layout1'
            ],
            1 => [
                0 => 'Layout 2',
                1 => 'layout2'
            ],
        ];

        $templateLayoutUtility = $this->getAccessibleMock(TemplateLayout::class, ['getTemplateLayoutsFromTsConfig']);
        $templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue([]));
        $templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
        $this->assertSame($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'], $templateLayouts);
    }

    /**
     * @test
     *
     * @return void
     */
    public function templatesFoundInPageTsConfig(): void
    {
        $tsConfigArray = [
            'layout1' => 'Layout 1',
            'layout2' => 'Layout 2',
        ];
        $result = [
            0 => [
                0 => 'Layout 1',
                1 => 'layout1'
            ],
            1 => [
                0 => 'Layout 2',
                1 => 'layout2'
            ],
        ];

        // clear TYPO3_CONF_VARS
        unset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts']);

        $templateLayoutUtility = $this->getAccessibleMock(TemplateLayout::class, ['getTemplateLayoutsFromTsConfig']);
        $templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue($tsConfigArray));
        $templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
        $this->assertSame($result, $templateLayouts);
    }

    /**
     * @test
     *
     * @return void
     */
    public function templatesFoundInCombinedResources(): void
    {
        $tsConfigArray = [
            'layout1' => 'Layout 1',
            'layout2' => 'Layout 2',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'] = [
            0 => [
                0 => 'Layout 4',
                1 => 'layout4'
            ],
        ];
        $result = [
            0 => [
                0 => 'Layout 4',
                1 => 'layout4'
            ],
            1 => [
                0 => 'Layout 1',
                1 => 'layout1'
            ],
            2 => [
                0 => 'Layout 2',
                1 => 'layout2'
            ],
        ];

        $templateLayoutUtility = $this->getAccessibleMock(TemplateLayout::class, ['getTemplateLayoutsFromTsConfig']);
        $templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue($tsConfigArray));
        $templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
        $this->assertSame($result, $templateLayouts);
    }
}
