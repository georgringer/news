<?php

namespace GeorgRinger\News\Tests\Unit\Service;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Service\CategoryService;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test class for CategoryService
 *
 */
class CategoryServiceTest extends UnitTestCase
{

    /**
     * @test
     * @dataProvider removeValuesFromStringDataProvider
     */
    public function removeValuesFromString($expected, $given)
    {
        $result = CategoryService::removeValuesFromString($given[0], $given[1]);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function removeValuesFromStringDataProvider()
    {
        return [
            'simpleExampleWithRemovalAtEnd' => [
                '1,2,3,4', ['1,2,3,4,5', '5']
            ],
            'simpleExampleWithMixedRemovals' => [
                '1,2,3,4', ['1,7,2,9,3,4', '9,7']
            ],
            'removalIsSame' => [
                '', ['1,2,3', '3,2,1']
            ],
            'noRemovalFound' => [
                '1,2,3', ['1,2,3', '9,8,7']
            ],
            'noInputGiven' => [
                '', ['', '9,8,7']
            ],
        ];
    }
}
