<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Repository;

use DateTimeImmutable;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\DateTimeAspect;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Functional test for the \GeorgRinger\News\Domain\Repository\NewsRepository
 */
class NewsRepositoryTest extends FunctionalTestCase
{
    /** @var  NewsRepository */
    protected $newsRepository;

    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];

    protected array $coreExtensionsToLoad = ['fluid'];

    public function setUp(): void
    {
        parent::setUp();
        $GLOBALS['TYPO3_REQUEST'] = (new ServerRequest())
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);

        $this->newsRepository = $this->getContainer()->get(NewsRepository::class);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tags.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_news.csv');
    }

    /**
     * Test if startingpoint is working
     */
    #[Test]
    public function findRecordsByUid(): void
    {
        $news = $this->newsRepository->findByUid(1);

        self::assertEquals('findRecordsByUid', $news->getTitle());
    }

    /**
     * Test if by import source is done
     */
    #[Test]
    public function findRecordsByImportSource(): void
    {
        $news = $this->newsRepository->findOneByImportSourceAndImportId('functional_test', '2');

        self::assertEquals('findRecordsByImportSource', $news->getTitle());
    }

    /**
     * Test if top news constraint works
     */
    #[Test]
    public function findTopNewsRecords(): void
    {
        $demand = new NewsDemand();
        $demand->setStoragePage(2);

        // no matter about top news
        $demand->setTopNewsRestriction(0);
        self::assertEquals(5, (int)$this->newsRepository->findDemanded($demand)->count());

        // Only Top news
        $demand->setTopNewsRestriction(1);
        self::assertEquals(3, (int)$this->newsRepository->findDemanded($demand)->count());

        // Only non Top news
        $demand->setTopNewsRestriction(2);
        self::assertEquals(2, (int)$this->newsRepository->findDemanded($demand)->count());
    }

    /**
     * Test if startingpoint is working
     */
    #[Test]
    public function findRecordsByStartingpointRestriction(): void
    {
        $demand = new NewsDemand();

        // simple starting point
        $demand->setStoragePage(3);
        self::assertEquals(2, (int)$this->newsRepository->findDemanded($demand)->count());

        // multiple starting points
        $demand->setStoragePage('4,5');
        self::assertEquals(5, (int)$this->newsRepository->findDemanded($demand)->count());

        // multiple starting points, including invalid values and commas
        $demand->setStoragePage('5 ,  x ,3');
        self::assertEquals(3, (int)$this->newsRepository->findDemanded($demand)->count());
    }

    /**
     * Test if record are found by archived/non archived flag
     */
    #[Test]
    public function findRecordsByArchiveRestriction(): void
    {
        GeneralUtility::makeInstance(Context::class)->setAspect(
            'date',
            new DateTimeAspect(new DateTimeImmutable('@1396812099'))
        );

        $demand = new NewsDemand();
        $demand->setStoragePage(7);

        // Archived means: archive date must be lower than current time AND != 0
        $demand->setArchiveRestriction('archived');
        self::assertEquals(3, (int)$this->newsRepository->findDemanded($demand)->count());

        // Active means: archive date must be in future or no archive date
        $demand->setArchiveRestriction('active');
        self::assertEquals(2, (int)$this->newsRepository->findDemanded($demand)->count());

        // no value means: give all
        $demand->setArchiveRestriction('');
        self::assertEquals(5, (int)$this->newsRepository->findDemanded($demand)->count());
    }

    /**
     * Test if record by month/year constraint works
     */
    #[Test]
    public function findRecordsByMonthAndYear(): void
    {
        $demand = new NewsDemand();
        $demand->setStoragePage(8);

        $demand->setDateField('datetime');
        $demand->setMonth('4');
        $demand->setYear('2011');
        self::assertEquals(1, (int)$this->newsRepository->findDemanded($demand)->count());
    }

    /**
     * Test if latest limit constraint works
     */
    #[Test]
    public function findLatestLimitRecords(): void
    {
        $demand = new NewsDemand();
        $demand->setStoragePage(9);

        GeneralUtility::makeInstance(Context::class)->setAspect(
            'date',
            new DateTimeAspect(new DateTimeImmutable('@' . strtotime('2014-04-03')))
        );

        // get all news maximum 6 days old
        $demand->setTimeRestriction(6 * 86400);
        self::assertEquals(4, (int)$this->newsRepository->findDemanded($demand)->count());

        // no restriction should get you all
        $demand->setTimeRestriction(0);
        self::assertEquals(6, (int)$this->newsRepository->findDemanded($demand)->count());
    }

    /**
     * Test if by import source is done
     */
    #[Test]
    public function findRecordsByTags(): void
    {
        $demand = new NewsDemand();
        $demand->setStoragePage(10);
        $demand->setOrder('uid');
        $demand->setOrderByAllowed('uid');

        // given is 1 tag
        $demand->setTags('3');
        $news = $this->newsRepository->findDemanded($demand);
        self::assertEquals('130,131', $this->getIdListOfNews($news));

        // given are 2 tags
        $demand->setTags('1,5');
        $news = $this->newsRepository->findDemanded($demand);
        self::assertEquals('130,133,134', $this->getIdListOfNews($news));

        // given are 3 real tags & 1 not existing
        $demand->setTags('5,3,1,10');
        $news = $this->newsRepository->findDemanded($demand);
        self::assertEquals('130,131,133,134', $this->getIdListOfNews($news));
    }

    #[Test]
    public function findRecordsForDateMenu(): void
    {
        $demand = new NewsDemand();
        $demand->setStoragePage('9');
        $demand->setDateField('datetime');
        $expected = [
            'single' => [
                '2014' => [
                    '03' => 4,
                    '04' => 2,
                ],
            ],
            'total' => [
                '2014' => 6,
            ],
        ];
        $dateMenuData = $this->newsRepository->countByDate($demand);
        self::assertEquals($expected, $dateMenuData);
    }

    /**
     * Test if records are found by type
     */
    #[Test]
    public function findRecordsByType(): void
    {
        $demand = new NewsDemand();
        $demand->setStoragePage('1,2,4561');

        // given is 1 tag
        $demand->setTypes(['1']);
        $count = $this->newsRepository->findDemanded($demand)->count();
        self::assertEquals(2, $count);

        // given are 2 tags
        $demand->setTypes(['1', 2]);
        $count = $this->newsRepository->findDemanded($demand)->count();
        self::assertEquals(6, $count);
    }

    protected function getIdListOfNews(QueryResultInterface $newsList): string
    {
        $idList = [];
        foreach ($newsList as $news) {
            $idList[] = $news->getUid();
        }
        return implode(',', $idList);
    }

    public function tearDown(): void
    {
        unset($this->newsRepository);
    }
}
