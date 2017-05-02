<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Utility\Validation;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for Validation
 *
 */
class ValidationTest extends UnitTestCase
{
    const ALLOWED_FIELDS = 'author,uid,title,teaser,author,tstamp,crdate,datetime,categories.title';

    /**
     * Test if default file format works
     *
     * @test
     * @dataProvider orderDataProvider
     */
    public function testForValidOrdering($expectedFields, $expected)
    {
        $validation = Validation::isValidOrdering($expectedFields, self::ALLOWED_FIELDS);
        $this->assertEquals($validation, $expected);
    }

    public function orderDataProvider()
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
