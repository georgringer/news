<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Repository;

use GeorgRinger\News\Domain\Repository\CategoryRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Functional test for the DataHandler
 */
class CategoryRepositoryTest extends FunctionalTestCase
{
    /** @var  CategoryRepository */
    protected $categoryRepository;

    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->getContainer()->get(CategoryRepository::class);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_category.csv');
    }

    /**
     * Test if by import source is done
     *
     * @test
     */
    public function findRecordByImportSource(): void
    {
        $category = $this->categoryRepository->findOneByImportSourceAndImportId('functional_test', '2');

        self::assertEquals($category->getTitle(), 'findRecordByImportSource');
    }
}
