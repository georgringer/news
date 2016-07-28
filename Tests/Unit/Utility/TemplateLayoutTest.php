<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

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
 * TemplateLayout utility class unit tests
 */
class TemplateLayoutTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @test
     */
    public function templatesFoundInTypo3ConfVars()
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

        $templateLayoutUtility = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TemplateLayout', ['getTemplateLayoutsFromTsConfig']);
        $templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue([]));
        $templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
        $this->assertSame($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'], $templateLayouts);
    }

    /**
     * @test
     */
    public function templatesFoundInPageTsConfig()
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

        $templateLayoutUtility = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TemplateLayout', ['getTemplateLayoutsFromTsConfig']);
        $templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue($tsConfigArray));
        $templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
        $this->assertSame($result, $templateLayouts);
    }

    /**
     * @test
     */
    public function templatesFoundInCombinedResources()
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

        $templateLayoutUtility = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TemplateLayout', ['getTemplateLayoutsFromTsConfig']);
        $templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue($tsConfigArray));
        $templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
        $this->assertSame($result, $templateLayouts);
    }
}
