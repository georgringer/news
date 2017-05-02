<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for domain repository categoryRepository
 *
 */
class CategoryRepositoryTest extends UnitTestCase
{

    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /**
     * @var array
     */
    private $backupGlobalVariables;

    public function setUp()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->backupGlobalVariables = [
            'BE_USER' => $GLOBALS['BE_USER'],
            'TSFE' => $GLOBALS['TSFE'],
        ];
    }

    public function tearDown()
    {
        foreach ($this->backupGlobalVariables as $key => $data) {
            $GLOBALS[$key] = $data;
        }
        unset($this->backupGlobalVariables);
    }

    /**
     * @test
     */
    public function correctSysLanguageIsReturnedUsingTsfe()
    {
        $objectManager = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface')->getMock();
        $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\CategoryRepository', ['dummy'], [$objectManager]);
        $result = $mockTemplateParser->_call('getSysLanguageUid');

        // Default value
        $this->assertEquals(0, $result);

        if (!is_object($GLOBALS['TSFE'])) {
            $vars = [];
            $type = 1;
            $GLOBALS['TSFE'] = new \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController($vars, 123, $type);
        }
        $GLOBALS['TSFE']->sys_language_content = 9;

        $result = $mockTemplateParser->_call('getSysLanguageUid');
        $this->assertEquals(9, $result);
    }

    /**
     * @test
     */
    public function correctSysLanguageIsReturnedUsingGetAndPostRequest()
    {
        /** @var \GeorgRinger\News\Domain\Repository\CategoryRepository $mockedCategoryRepository */
        if (version_compare(TYPO3_branch, '6.2', '>=')) {
            $objectManager = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface')->getMock();
            $mockedCategoryRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\CategoryRepository', ['dummy'], [$objectManager]);
        } else {
            $mockedCategoryRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\CategoryRepository', ['dummy']);
        }
        unset($GLOBALS['TSFE']);

        // Default value
        $result = $mockedCategoryRepository->_call('getSysLanguageUid');
        $this->assertEquals(0, $result);

        // GET
        $_GET['L'] = 11;
        $result = $mockedCategoryRepository->_call('getSysLanguageUid');
        $this->assertEquals(11, $result);

        // POST
        $_POST['L'] = 13;
        $result = $mockedCategoryRepository->_call('getSysLanguageUid');
        $this->assertEquals(13, $result);

        // POST overrules GET
        $_GET['L'] = 15;
        $this->assertEquals(13, $result);
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
        if (version_compare(TYPO3_branch, '6.2', '>=')) {
            $objectManager = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface')->getMock();
            $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\CategoryRepository', ['dummy'], [$objectManager]);
        } else {
            $mockTemplateParser = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\CategoryRepository', ['dummy']);
        }

        $result = $mockTemplateParser->_call('replaceCategoryIds', $given['idList'], $given['toBeChanged']);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function categoryIdsAreCorrectlyReplacedDataProvider()
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
