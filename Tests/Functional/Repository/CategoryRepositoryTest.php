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

    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var  \GeorgRinger\News\Domain\Repository\CategoryRepository */
    protected $categoryRepository;

    protected $testExtensionsToLoad = ['typo3conf/ext/news'];

    public function setUp(): void
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->categoryRepository = $this->objectManager->get(CategoryRepository::class);

        $this->importDataSet(__DIR__ . '/../Fixtures/sys_category.xml');
    }

    /**
     * Test if by import source is done
     *
     * @test
     */
    public function findRecordByImportSource()
    {
        $category = $this->categoryRepository->findOneByImportSourceAndImportId('functional_test', '2');

        $this->assertEquals($category->getTitle(), 'findRecordByImportSource');
    }
}
