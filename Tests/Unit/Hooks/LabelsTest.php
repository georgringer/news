<?php

namespace GeorgRinger\News\Tests\Unit\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for Labels
 */
class LabelsTest extends UnitTestCase
{

    /**
     * @test
     * @dataProvider correctFieldOfArrayIsReturnedDataProvider
     */
    public function correctFieldOfArrayIsReturned($input, $expectedResult)
    {
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\Hooks\\Labels', ['dummy']);
        $result = $mockTemplateParser->_call('getTitleFromFields', $input[0], $input[1]);
        $this->assertEquals($expectedResult, $result);
    }

    public function correctFieldOfArrayIsReturnedDataProvider()
    {
        return [
            'working example 1' => [
                ['uid, title', ['title' => 'fobar']], 'fobar'
            ],
            '1st found result is returned' => [
                ['uid, title', ['uid' => '123', 'title' => 'fobar']], '123'
            ],
            'empty fieldlist returns empty string' => [
                ['', ['title' => 'fobar']], ''
            ],
            'empty array returns empty string' => [
                ['uid, title', []], ''
            ],
            'null returns empty string' => [
                ['uid, title', null], ''
            ],
        ];
    }

    /**
     * @test
     * @dataProvider splitOfFileNameReturnsCorrectPartialDataProvider
     */
    public function splitOfFileNameReturnsCorrectPartial($string, $expectedResult)
    {
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\Hooks\\Labels', ['dummy']);
        $result = $mockTemplateParser->_call('splitFileName', $string);
        $this->assertEquals($expectedResult, $result);
    }

    public function splitOfFileNameReturnsCorrectPartialDataProvider()
    {
        return [
            'working example 1' => [
                'fobar|fobar', 'fobar'
            ],
            'different strings' => [
                'fo|bar', 'fo|bar'
            ],
            'wrong count 1' => [
                'fo|bar|xxx', 'fo|bar|xxx'
            ],
            'wrong count 2' => [
                'fobar', 'fobar'
            ],
        ];
    }
}
