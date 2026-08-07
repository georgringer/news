<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Service;

use GeorgRinger\News\Service\CategoryService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Functional test for the CategoryService
 */
#[IgnoreDeprecations]
class CategoryServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];

    public function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_category_tree.csv');
    }

    public static function getChildrenCategoriesDataProvider(): array
    {
        return [
            'category without children returns itself' => [
                '20', [20],
            ],
            'category with one level of children' => [
                '10', [10, 11],
            ],
            'category with nested children' => [
                '1', [1, 2, 3, 4, 5],
            ],
            'subcategory returns only its own subtree' => [
                '2', [2, 3, 4],
            ],
            'multiple categories are resolved together' => [
                '1,10', [1, 2, 3, 4, 5, 10, 11],
            ],
            'hidden categories are included, deleted ones are not' => [
                '30', [30, 31],
            ],
            'unknown category returns itself' => [
                '999', [999],
            ],
        ];
    }

    #[DataProvider('getChildrenCategoriesDataProvider')]
    #[Test]
    public function getChildrenCategoriesResolvesTheWholeSubtree(string $idList, array $expected): void
    {
        $result = GeneralUtility::intExplode(',', CategoryService::getChildrenCategories($idList), true);
        sort($result);

        self::assertSame($expected, $result);
    }

    #[Test]
    public function getChildrenCategoriesKeepsChildrenBehindTheirParent(): void
    {
        // the subtree of a category has to directly follow the category itself
        self::assertSame('1,2,3,4,5', CategoryService::getChildrenCategories('1'));
    }

    #[Test]
    public function getChildrenCategoriesTerminatesOnCyclicCategories(): void
    {
        $result = GeneralUtility::intExplode(',', CategoryService::getChildrenCategories('40'), true);
        sort($result);

        self::assertSame([40, 41], $result);
    }

    #[Test]
    public function getChildrenCategoriesCanRemoveTheGivenIdListFromTheResult(): void
    {
        self::assertSame('2,3,4,5', CategoryService::getChildrenCategories('1', 0, '', true));
    }
}
