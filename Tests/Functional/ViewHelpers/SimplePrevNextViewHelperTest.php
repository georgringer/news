<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\ViewHelpers;

use DateTime;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class SimplePrevNextViewHelperTest
 */
class SimplePrevNextViewHelperTest extends FunctionalTestCase
{
    /** @var \GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper|\PHPUnit\Framework\MockObject\MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface */
    protected $mockedViewHelper;

    /** @var News */
    protected $news;

    protected array $testExtensionsToLoad = ['typo3conf/ext/news'];
    protected array $coreExtensionsToLoad = ['extbase', 'fluid'];

    public function setUp(): void
    {
        parent::setUp();
        $this->mockedViewHelper = $this->getAccessibleMock(SimplePrevNextViewHelper::class, null, [], '', true, true, false);

        $this->news = new News();
        $this->news->_setProperty('uid', 123);
        $this->news->setPid(9);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_news.csv');
    }

    /**
     * @test
     */
    public function allNeighboursCanBeFound(): void
    {
        $this->setDate(1396035186);
        $actual = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

        $exp = [
            'prev' => $this->getRow(102),
            'next' => $this->getRow(104),
        ];
        self::assertEquals($exp, $actual);
    }

    /**
     * @test
     */
    public function nextNeighbourCanBeFound(): void
    {
        $this->setDate(1395516730);

        $actual = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

        $exp = [
            'next' => $this->getRow(102),
        ];
        self::assertEquals($exp, $actual);
    }

    /**
     * @test
     */
    public function previousNeighbourCanBeFound(): void
    {
        $this->setDate(1396640035);
        $actual = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');
        $exp = [
            'prev' => $this->getRow(105),
        ];
        self::assertEquals($exp, $actual);
    }

    /**
     * @param int $timestamp
     */
    protected function setDate($timestamp): void
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        $this->news->_setProperty('datetime', $date);
    }

    /**
     * @param int $id
     */
    protected function getRow(int $id)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');
        return $queryBuilder
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id, Connection::PARAM_INT))
            )
            ->setMaxResults(1)
            ->executeQuery()->fetchAssociative();
    }
}
