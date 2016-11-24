<?php

namespace GeorgRinger\News\Tests\Unit\Functional\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Functional test for the \GeorgRinger\News\Domain\Repository\NewsRepository
 */
class NewsRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{

    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var  \GeorgRinger\News\Domain\Repository\NewsRepository */
    protected $newsRepository;

    protected $testExtensionsToLoad = ['typo3conf/ext/news'];

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->newsRepository = $this->objectManager->get('GeorgRinger\\News\\Domain\\Repository\\NewsRepository');

        $this->importDataSet(__DIR__ . '/../Fixtures/tags.xml');
        $this->importDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_news.xml');
    }

    /**
     * Test if startingpoint is working
     *
     * @test
     * @return void
     */
    public function findRecordsByUid()
    {
        $news = $this->newsRepository->findByUid(1);

        $this->assertEquals($news->getTitle(), 'findRecordsByUid');
    }

    /**
     * Test if by import source is done
     *
     * @test
     * @return void
     */
    public function findRecordsByImportSource()
    {
        $news = $this->newsRepository->findOneByImportSourceAndImportId('functional_test', '2');

        $this->assertEquals($news->getTitle(), 'findRecordsByImportSource');
    }

    /**
     * Test if top news constraint works
     *
     * @test
     * @return void
     */
    public function findTopNewsRecords()
    {
        /** @var $demand \GeorgRinger\News\Domain\Model\Dto\NewsDemand */
        $demand = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\Dto\\NewsDemand');
        $demand->setStoragePage(2);

        // no matter about top news
        $demand->setTopNewsRestriction(0);
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 5);

        // Only Top news
        $demand->setTopNewsRestriction(1);
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 3);

        // Only non Top news
        $demand->setTopNewsRestriction(2);
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 2);
    }

    /**
     * Test if startingpoint is working
     *
     * @test
     * @return void
     */
    public function findRecordsByStartingpointRestriction()
    {
        /** @var $demand \GeorgRinger\News\Domain\Model\Dto\NewsDemand */
        $demand = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand');

        // simple starting point
        $demand->setStoragePage(3);
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 2);

        // multiple starting points
        $demand->setStoragePage('4,5');
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 5);

        // multiple starting points, including invalid values and commas
        $demand->setStoragePage('5 ,  x ,3');
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 3);
    }

    /**
     * Test if record are found by archived/non archived flag
     *
     * @test
     * @return void
     */
    public function findRecordsByArchiveRestriction()
    {
        $GLOBALS['EXEC_TIME'] = 1396812099;
        $newsRepository = $this->objectManager->get('GeorgRinger\\News\\Domain\\Repository\\NewsRepository');

        /** @var $demand \GeorgRinger\News\Domain\Model\Dto\NewsDemand */
        $demand = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand');
        $demand->setStoragePage(7);

        // Archived means: archive date must be lower than current time AND != 0
        $demand->setArchiveRestriction('archived');
        $this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 3);

        // Active means: archive date must be in future or no archive date
        $demand->setArchiveRestriction('active');
        $this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 2);

        // no value means: give all
        $demand->setArchiveRestriction('');
        $this->assertEquals((int)$newsRepository->findDemanded($demand)->count(), 5);
    }

    /**
     * Test if record by month/year constraint works
     *
     * @test
     * @return void
     */
    public function findRecordsByMonthAndYear()
    {
        $this->markTestSkipped('Does not work in travis');
        /** @var $demand \GeorgRinger\News\Domain\Model\Dto\NewsDemand */
        $demand = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand');
        $demand->setStoragePage(8);

        $demand->setDateField('datetime');
        $demand->setMonth('4');
        $demand->setYear('2011');
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 1);
    }

    /**
     * Test if latest limit constraint works
     *
     * @test
     * @return void
     */
    public function findLatestLimitRecords()
    {
        /** @var $demand \GeorgRinger\News\Domain\Model\Dto\NewsDemand */
        $demand = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand');
        $demand->setStoragePage(9);

        $GLOBALS['EXEC_TIME'] = strtotime('2014-04-03');

        // get all news maximum 6 days old
        $demand->setTimeRestriction((6 * 86400));
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 4);

        // no restriction should get you all
        $demand->setTimeRestriction(0);
        $this->assertEquals((int)$this->newsRepository->findDemanded($demand)->count(), 6);
    }

    /**
     * Test if by import source is done
     *
     * @test
     * @return void
     */
    public function findRecordsByTags()
    {
        /** @var \GeorgRinger\News\Domain\Model\Dto\NewsDemand $demand */
        $demand = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\Dto\\NewsDemand');
        $demand->setStoragePage(10);
        $demand->setOrder('uid');
        $demand->setOrderByAllowed('uid');

        // given is 1 tag
        $demand->setTags('3');
        $news = $this->newsRepository->findDemanded($demand);
        $this->assertEquals('130,131', $this->getIdListOfNews($news));

        // given are 2 tags
        $demand->setTags('1,5');
        $news = $this->newsRepository->findDemanded($demand);
        $this->assertEquals('130,133,134', $this->getIdListOfNews($news));

        // given are 3 real tags & 1 not existing
        $demand->setTags('5,3,1,10');
        $news = $this->newsRepository->findDemanded($demand);
        $this->assertEquals('130,131,133,134', $this->getIdListOfNews($news));
    }

    /**
     * @test
     */
    public function findRecordsForDateMenu()
    {
        $demand = $this->objectManager->get(NewsDemand::class);
        $demand->setStoragePage('9');
        $demand->setDateField('datetime');
        $expected = [
            'single' => [
                '2014' => [
                    '03' => 4,
                    '04' => 2
                ]
            ],
            'total' => [
                '2014' => 6
            ]
        ];
        $dateMenuData = $this->newsRepository->countByDate($demand);
        $this->assertEquals($expected, $dateMenuData);
    }


    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $newsList
     * @return string
     */
    protected function getIdListOfNews(\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $newsList)
    {
        $idList = [];
        foreach ($newsList as $news) {
            $idList[] = $news->getUid();
        }
        return implode(',', $idList);
    }

    public function tearDown()
    {
        unset($this->newsRepository);
        unset($this->objectManager);
    }
}
