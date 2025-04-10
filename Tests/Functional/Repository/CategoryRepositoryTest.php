<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Repository;

use GeorgRinger\News\Domain\Repository\CategoryRepository;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
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

        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupArray([]);
        $GLOBALS['TYPO3_REQUEST'] = (new ServerRequest())
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('frontend.typoscript', $frontendTypoScript);

        $this->categoryRepository = $this->getContainer()->get(CategoryRepository::class);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_category.csv');
    }

    /**
     * Test if by import source is done
     */
    #[Test]
    #[IgnoreDeprecations]
    public function findRecordByImportSource(): void
    {
        $category = $this->categoryRepository->findOneByImportSourceAndImportId('functional_test', '2');

        self::assertEquals('findRecordByImportSource', $category->getTitle());
    }
}
