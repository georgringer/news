<?php

namespace GeorgRinger\News\Tests\Functional\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Functional test for the DataHandler
 */
class CategoryRepositoryTest extends FunctionalTestCase
{
    /** @var  \GeorgRinger\News\Domain\Repository\CategoryRepository */
    protected $categoryRepository;

    protected $testExtensionsToLoad = ['typo3conf/ext/news'];

    public function setUp(): void
    {
        parent::setUp();
        $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
        if ($versionInformation->getMajorVersion() === 11) {
            $this->categoryRepository = $this->getContainer()->get(CategoryRepository::class);
        } else {
            $this->categoryRepository = GeneralUtility::makeInstance(ObjectManager::class)->get(CategoryRepository::class);
        }

        $this->importDataSet(__DIR__ . '/../Fixtures/sys_category.xml');
    }

    /**
     * Test if by import source is done
     *
     * @test
     *
     * @return void
     */
    public function findRecordByImportSource(): void
    {
        $category = $this->categoryRepository->findOneByImportSourceAndImportId('functional_test', '2');

        $this->assertEquals($category->getTitle(), 'findRecordByImportSource');
    }
}
