<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

use GeorgRinger\News\ViewHelpers\PaginateBodytextViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for PaginateBodytextViewHelper
 */
class PaginateBodytextViewHelperTest extends BaseTestCase
{
    /**
     * Test if given tag is a closing tag
     *
     * @test
     *
     * @dataProvider givenTagIsAClosingTagDataProvider
     */
    public function givenTagIsAClosingTag($tag, $expectedResult): void
    {
        $mockTemplateParser = $this->getAccessibleMock(PaginateBodytextViewHelper::class, null);
        $result = $mockTemplateParser->_call('isClosingTag', $tag);
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @return (bool|string)[][]
     *
     * @psalm-return array{'working example 1': array{0: string, 1: true}, 'working example 2': array{0: string, 1: false}}
     */
    public function givenTagIsAClosingTagDataProvider(): array
    {
        return [
            'working example 1' => [
                '</div>', true,
            ],
            'working example 2' => [
                '<div>', false,
            ],
        ];
    }

    /**
     * Test if given tag is a self closing tag
     *
     * @test
     *
     * @dataProvider givenTagIsSelfClosingTagDataProvider
     */
    public function givenTagIsSelfClosingTag($tag, $expectedResult): void
    {
        $mockTemplateParser = $this->getAccessibleMock(PaginateBodytextViewHelper::class, null);
        $result = $mockTemplateParser->_call('isSelfClosingTag', $tag);
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @return (bool|string)[][]
     *
     * @psalm-return array{'working example 1': array{0: string, 1: true}, 'working example 2': array{0: string, 1: false}}
     */
    public function givenTagIsSelfClosingTagDataProvider(): array
    {
        return [
            'working example 1' => [
                '<hr />', true,
            ],
            'working example 2' => [
                '<div>', false,
            ],
        ];
    }

    /**
     * Test if given tag is an opening tag
     *
     * @test
     *
     * @dataProvider givenTagIsAnOpeningTagDataProvider
     */
    public function givenTagIsAnOpeningTag($tag, $expectedResult): void
    {
        $mockTemplateParser = $this->getAccessibleMock(PaginateBodytextViewHelper::class, null);
        $result = $mockTemplateParser->_call('isOpeningTag', $tag);
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @return (bool|string)[][]
     *
     * @psalm-return array{0: array{0: string, 1: true}, 1: array{0: string, 1: false}, 2: array{0: string, 1: false}, 3: array{0: string, 1: false}}
     */
    public function givenTagIsAnOpeningTagDataProvider(): array
    {
        return [
            ['<div>', true],
            ['</div>', false],
            ['<div/>', false],
            ['<div />', false],
        ];
    }

    /**
     * Test if given tag is an opening tag
     *
     * @test
     *
     * @dataProvider extractTagReturnsCorrectOneDataProvider
     */
    public function extractTagReturnsCorrectOne($tag, $expectedResult): void
    {
        $mockTemplateParser = $this->getAccessibleMock(PaginateBodytextViewHelper::class, null);
        $result = $mockTemplateParser->_call('extractTag', $tag);
        self::assertEquals($expectedResult, $result, sprintf('"%s" (%s) : "%s" (%s)', $tag, strlen($tag), $expectedResult, strlen($expectedResult)));
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{0: array{0: string, 1: string}, 1: array{0: string, 1: string}, 2: array{0: string, 1: string}, 3: array{0: string, 1: string}}
     */
    public function extractTagReturnsCorrectOneDataProvider(): array
    {
        return [
            ['this <strong>is</strong> a <div>real</div>test', 'this <strong>'],
            ['this <br /> linebreak', 'this <br />'],
            ['this <br> linebreak', 'this <br>'],
            ['this is a test', 'this is a test'],
        ];
    }
}
