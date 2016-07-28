<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Widget\Controller;

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
 * Tests for PaginateController
 *
 */
class PaginateControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \GeorgRinger\News\ViewHelpers\Widget\Controller\PaginateController
     */
    protected $controller;

    /**
     * Sets up this test case
     *
     * @return void
     */
    public function setUp()
    {
        $this->controller = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\Widget\\Controller\\PaginateController', ['dummy'], [], '', false);
    }

    /**
     * @test
     */
    public function initializationIsCorrect()
    {
        $controller = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\Widget\\Controller\\PaginateController', ['dummy']);
        $objects = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $configuration = [
            'templatePath' => 'fo/bar',
            'itemsPerPage' => '3',
        ];
        $widgetConfiguration = ['fo' => 'bar'];
        $controller->_set('configuration', $configuration);
        $controller->_set(
            'widgetConfiguration',
            [
                'configuration' => $widgetConfiguration,
                'objects' => $objects
            ]);

        $controller->initializeAction();
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($configuration, $widgetConfiguration, true);
        $this->assertEquals($controller->_get('objects'), $objects);
        $this->assertEquals($controller->_get('configuration'), $configuration);
        $this->assertEquals($controller->_get('numberOfPages'), 5);
        $this->assertEquals($controller->_get('templatePath'), PATH_site . 'fo/bar');
    }

    /**
     * @test
     */
    public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForEvenMaximumNumberOfLinks()
    {
        $this->controller->_set('maximumNumberOfLinks', 8);
        $this->controller->_set('numberOfPages', 100);
        $this->controller->_set('currentPage', 50);
        $this->controller->_call('calculateDisplayRange');
        $this->assertSame(46, $this->controller->_get('displayRangeStart'));
        $this->assertSame(53, $this->controller->_get('displayRangeEnd'));
    }

    /**
     * @test
     */
    public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForOddMaximumNumberOfLinks()
    {
        $this->controller->_set('maximumNumberOfLinks', 7);
        $this->controller->_set('numberOfPages', 100);
        $this->controller->_set('currentPage', 50);
        $this->controller->_call('calculateDisplayRange');
        $this->assertSame(47, $this->controller->_get('displayRangeStart'));
        $this->assertSame(53, $this->controller->_get('displayRangeEnd'));
    }

    /**
     * @test
     */
    public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForEvenMaximumNumberOfLinksWhenOnFirstPage()
    {
        $this->controller->_set('maximumNumberOfLinks', 8);
        $this->controller->_set('numberOfPages', 100);
        $this->controller->_set('currentPage', 1);
        $this->controller->_call('calculateDisplayRange');
        $this->assertSame(1, $this->controller->_get('displayRangeStart'));
        $this->assertSame(8, $this->controller->_get('displayRangeEnd'));
    }

    /**
     * @test
     */
    public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForOddMaximumNumberOfLinksWhenOnFirstPage()
    {
        $this->controller->_set('maximumNumberOfLinks', 7);
        $this->controller->_set('numberOfPages', 100);
        $this->controller->_set('currentPage', 1);
        $this->controller->_call('calculateDisplayRange');
        $this->assertSame(1, $this->controller->_get('displayRangeStart'));
        $this->assertSame(7, $this->controller->_get('displayRangeEnd'));
    }

    /**
     * @test
     */
    public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForEvenMaximumNumberOfLinksWhenOnLastPage()
    {
        $this->controller->_set('maximumNumberOfLinks', 8);
        $this->controller->_set('numberOfPages', 100);
        $this->controller->_set('currentPage', 100);
        $this->controller->_call('calculateDisplayRange');
        $this->assertSame(93, $this->controller->_get('displayRangeStart'));
        $this->assertSame(100, $this->controller->_get('displayRangeEnd'));
    }

    /**
     * @test
     */
    public function calculateDisplayRangeDeterminesCorrectDisplayRangeStartAndEndForOddMaximumNumberOfLinksWhenOnLastPage()
    {
        $this->controller->_set('maximumNumberOfLinks', 7);
        $this->controller->_set('numberOfPages', 100);
        $this->controller->_set('currentPage', 100);
        $this->controller->_call('calculateDisplayRange');
        $this->assertSame(94, $this->controller->_get('displayRangeStart'));
        $this->assertSame(100, $this->controller->_get('displayRangeEnd'));
    }
}
