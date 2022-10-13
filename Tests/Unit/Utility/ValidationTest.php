<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Utility\Validation;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for Validation
 *
 */
class ValidationTest extends BaseTestCase
{
    public const ALLOWED_FIELDS = 'author,uid,title,teaser,author,tstamp,crdate,datetime,categories.title';

    /**
     * Test if default file format works
     *
     * @test
     *
     * @dataProvider orderDataProvider
     *
     * @return void
     */
    public function testForValidOrdering($expectedFields, $expected): void
    {
        $validation = Validation::isValidOrdering($expectedFields, self::ALLOWED_FIELDS);
        $this->assertEquals($validation, $expected);
    }

    /**
     * @return (bool|string)[][]
     *
     * @psalm-return array{allowedOrdering: array{0: string, 1: true}, allowedOrderingWithSorting: array{0: string, 1: true}, allowedOrderingWithSorting2: array{0: string, 1: true}, allowedOrderingWithSorting3: array{0: string, 1: true}, allowedOrderingWithDotsAndSorting: array{0: string, 1: true}, nonAllowedField: array{0: string, 1: false}, nonAllowedSorting: array{0: string, 1: false}, nonAllowedDoubleSorting: array{0: string, 1: false}, nonAllowedDoubleFields: array{0: string, 1: false}, emptySorting: array{0: string, 1: true}, emptySorting2: array{0: string, 1: true}}
     */
    public function orderDataProvider(): array
    {
        return [
            'allowedOrdering' => [
                'title,uid', true
            ],
            'allowedOrderingWithSorting' => [
                'title ASC, uid', true
            ],
            'allowedOrderingWithSorting2' => [
                'title ASC, uid DESC', true
            ],
            'allowedOrderingWithSorting3' => [
                'title, uid desc,teaser', true
            ],
            'allowedOrderingWithDotsAndSorting' => [
                'categories.title DESC, uid ASC,author,teaser desc', true
            ],
            'nonAllowedField' => [
                'title,teaserFo,uid', false
            ],
            'nonAllowedSorting' => [
                'title,teaser ASCx,uid', false
            ],
            'nonAllowedDoubleSorting' => [
                'title,teaser ASC DESC,uid', false
            ],
            'nonAllowedDoubleFields' => [
                'title teaser,uid', false
            ],
            'emptySorting' => [
                '', true
            ],
            'emptySorting2' => [
                ' ', true
            ],

        ];
    }
}
