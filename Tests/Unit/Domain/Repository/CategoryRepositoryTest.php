<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Repository\CategoryRepository;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for domain repository categoryRepository
 *
 */
class CategoryRepositoryTest extends BaseTestCase
{

    /**
     * @var array
     */
    private $backupGlobalVariables;

    public function setup(): void
    {
        $this->backupGlobalVariables = [
            'BE_USER' => $GLOBALS['BE_USER'],
            'TSFE' => $GLOBALS['TSFE'],
        ];
    }

    public function tearDown(): void
    {
        foreach ($this->backupGlobalVariables as $key => $data) {
            $GLOBALS[$key] = $data;
        }
        unset($this->backupGlobalVariables);
    }

    /**
     * Test if category ids are replaced
     *
     * @param array $expectedResult
     * @param array $given
     * @test
     * @dataProvider categoryIdsAreCorrectlyReplacedDataProvider
     */
    public function categoryIdsAreCorrectlyReplaced($expectedResult, $given)
    {
        $mockTemplateParser = $this->getAccessibleMock(CategoryRepository::class, ['dummy'], [], '', false);

        $result = $mockTemplateParser->_call('replaceCategoryIds', $given['idList'], $given['toBeChanged']);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function categoryIdsAreCorrectlyReplacedDataProvider(): array
    {
        return [
            'emptyRows' => [
                [1, 2, 3],
                [
                    'idList' => [1, 2, 3],
                    'toBeChanged' => []
                ]
            ],
            'oneIdReplaced' => [
                [1, 5, 3],
                [
                    'idList' => [1, 2, 3],
                    'toBeChanged' => [
                        0 => [
                            'l10n_parent' => 2,
                            'uid' => 5,
                        ]
                    ]
                ]
            ],
            'noIdReplaced' => [
                [1, 2, 3],
                [
                    'idList' => [1, 2, 3],
                    'toBeChanged' => [
                        0 => [
                            'l10n_parent' => 6,
                            'uid' => 5,
                        ],
                        1 => [
                            'l10n_parent' => 8,
                            'uid' => 10,
                        ]
                    ]
                ]
            ],
            'allIdsReplaced' => [
                [4, 5, 6],
                [
                    'idList' => [1, 2, 3],
                    'toBeChanged' => [
                        [
                            'l10n_parent' => 2,
                            'uid' => 5,
                        ],
                        [
                            'l10n_parent' => 1,
                            'uid' => 4,
                        ],
                        [
                            'l10n_parent' => 3,
                            'uid' => 6,
                        ],
                        [
                            'l10n_parent' => 8,
                            'uid' => 10,
                        ]
                    ]
                ]
            ],
        ];
    }
}
