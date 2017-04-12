<?php

namespace GeorgRinger\News\Tests\Unit\Functional\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use DateTime;
use GeorgRinger\News\Domain\Model\News;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Class SimplePrevNextViewHelperTest
 *
 */
class SimplePrevNextViewHelperTest extends FunctionalTestCase
{

    /** @var \PHPUnit_Framework_MockObject_MockObject|AccessibleMockObjectInterface|\GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper */
    protected $mockedViewHelper;

    /** @var \GeorgRinger\News\Domain\Model\News */
    protected $news;

    protected $testExtensionsToLoad = ['typo3conf/ext/news'];
    protected $coreExtensionsToLoad = ['extbase', 'fluid'];

    public function setUp()
    {
        parent::setUp();
        $this->mockedViewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\SimplePrevNextViewHelper', ['dummy'], [], '', true, true, false);

        $this->news = new News();
        $this->news->setPid(9);

        $this->importDataSet(__DIR__ . '/../Fixtures/tx_news_domain_model_news.xml');
    }

    /**
     * @test
     */
    public function allNeighboursCanBeFound()
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
     */
    public function nextNeighbourCanBeFound()
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
     */
    public function previousNeighbourCanBeFound()
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
     */
    protected function setDate($timestamp)
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        $this->news->_setProperty('datetime', $date);
    }

    protected function getRow($id)
    {
        return $this->getDb()->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_news', 'uid=' . (int)$id);
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDb()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
