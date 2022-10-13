<?php

namespace GeorgRinger\News\Tests\Functional\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DateTime;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class SimplePrevNextViewHelperTest
 *
 */
class SimplePrevNextViewHelperTest extends FunctionalTestCase
{
    /** @var \GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper|\PHPUnit\Framework\MockObject\MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface */
    protected $mockedViewHelper;

    /** @var News */
    protected $news;

    protected $testExtensionsToLoad = ['typo3conf/ext/news'];
    protected $coreExtensionsToLoad = ['extbase', 'fluid'];

    public function setUp(): void
    {
        parent::setUp();
        $this->mockedViewHelper = $this->getAccessibleMock(SimplePrevNextViewHelper::class, ['dummy'], [], '', true, true, false);

        $this->news = new News();
        $this->news->_setProperty('uid', 123);
        $this->news->setPid(9);

        $this->importDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_news.xml');
    }

    /**
     * @test
     *
     * @return void
     */
    public function allNeighboursCanBeFound(): void
    {
        $this->setDate(1396035186);
        $actual = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

        $exp = [
            'prev' => $this->getRow(102),
            'next' => $this->getRow(104)
        ];
        $this->assertEquals($exp, $actual);
    }

    /**
     * @test
     *
     * @return void
     */
    public function nextNeighbourCanBeFound(): void
    {
        $this->setDate(1395516730);

        $actual = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');

        $exp = [
            'next' => $this->getRow(102)
        ];
        $this->assertEquals($exp, $actual);
    }

    /**
     * @test
     *
     * @return void
     */
    public function previousNeighbourCanBeFound(): void
    {
        $this->setDate(1396640035);
        $actual = $this->mockedViewHelper->_call('getNeighbours', $this->news, '', 'datetime');
        $exp = [
            'prev' => $this->getRow(105)
        ];
        $this->assertEquals($exp, $actual);
    }

    /**
     * @param int $timestamp
     *
     * @return void
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
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->setMaxResults(1)
            ->execute()->fetch();
    }
}
