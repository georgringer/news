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

/**
 * Tests for PaginateBodytextViewHelper
 *
 */
class PaginateBodytextViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Test if given tag is a closing tag
     *
     * @test
     * @dataProvider givenTagIsAClosingTagDataProvider
     * @return void
     */
    public function givenTagIsAClosingTag($tag, $expectedResult)
    {
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\PaginateBodytextViewHelper', ['dummy']);
        $result = $mockTemplateParser->_call('isClosingTag', $tag);
        $this->assertEquals($expectedResult, $result);
    }

    public function givenTagIsAClosingTagDataProvider()
    {
        return [
            'working example 1' => [
                '</div>', true
            ],
            'working example 2' => [
                '<div>', false
            ],
        ];
    }

    /**
     * Test if given tag is a self closing tag
     *
     * @test
     * @dataProvider givenTagIsSelfClosingTagDataProvider
     * @return void
     */
    public function givenTagIsSelfClosingTag($tag, $expectedResult)
    {
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\PaginateBodytextViewHelper', ['dummy']);
        $result = $mockTemplateParser->_call('isSelfClosingTag', $tag);
        $this->assertEquals($expectedResult, $result);
    }

    public function givenTagIsSelfClosingTagDataProvider()
    {
        return [
            'working example 1' => [
                '<hr />', true
            ],
            'working example 2' => [
                '<div>', false
            ],
        ];
    }

    /**
     * Test if given tag is an opening tag
     *
     * @test
     * @dataProvider givenTagIsAnOpeningTagDataProvider
     * @return void
     */
    public function givenTagIsAnOpeningTag($tag, $expectedResult)
    {
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\PaginateBodytextViewHelper', ['dummy']);
        $result = $mockTemplateParser->_call('isOpeningTag', $tag);
        $this->assertEquals($expectedResult, $result);
    }

    public function givenTagIsAnOpeningTagDataProvider()
    {
        return [
            ['<div>', true],
            ['</div>', false],
            ['<div/>', false],
            ['<div />', false]
        ];
    }

    /**
     * Test if given tag is an opening tag
     *
     * @test
     * @dataProvider extractTagReturnsCorrectOneDataProvider
     * @return void
     */
    public function extractTagReturnsCorrectOne($tag, $expectedResult)
    {
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\PaginateBodytextViewHelper', ['dummy']);
        $result = $mockTemplateParser->_call('extractTag', $tag);
        $this->assertEquals($expectedResult, $result, sprintf('"%s" (%s) : "%s" (%s)', $tag, strlen($tag), $expectedResult, strlen($expectedResult)), 1);
    }

    public function extractTagReturnsCorrectOneDataProvider()
    {
        return [
            ['this <strong>is</strong> a <div>real</div>test', 'this <strong>'],
            ['this <br /> linebreak', 'this <br />'],
            ['this <br> linebreak', 'this <br>'],
            ['this is a test', 'this is a test'],
        ];
    }
}
