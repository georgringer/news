<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Domain\Repository;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Model\Dto\Search;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\Comparison;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\LogicalOr;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\PropertyValue;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\Statement;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\TestingFramework\Core\BaseTestCase;
use UnexpectedValueException;

/**
 * Tests for domain repository newsRepository
 */
class NewsRepositoryTest extends BaseTestCase
{
    /** @var \GeorgRinger\News\Domain\Repository\NewsRepository|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface */
    protected $mockedNewsRepository;

    public function setup(): void
    {
        $this->mockedNewsRepository = $this->getAccessibleMock(NewsRepository::class, ['getQueryBuilder'], [], '', false);

        $mockedQueryBuilder = $this->getAccessibleMock(QueryBuilder::class, ['createNamedParameter'], [], '', false);
        $this->mockedNewsRepository->expects(self::any())->method('getQueryBuilder')->withAnyParameters()->willReturn($mockedQueryBuilder);
    }

    /**
     * @test
     */
    public function getSearchConstraintsThrowsErrorIfNoSearchFieldIsGiven(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();

        $search = new Search();
        $search->setSubject('fo');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
    }
    //
    /**
     * @test
     */
    public function getSearchConstraintsThrowsErrorIfNoDateFieldForMaximumDateIsGiven(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();

        $search = new Search();
        $search->setMaximumDate('2014-04-01');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
    }
    //
    /**
     * @test
     */
    public function getSearchConstraintsThrowsErrorIfNoDateFieldForMinimumDateIsGiven(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();

        $search = new Search();
        $search->setDateField('');
        $search->setMinimumDate('2014-04-01');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
    }
    //
    /**
     * @test
     */
    public function emptyConstraintIsReturnedForEmptySearchDemand(): void
    {
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();

        $demand = new NewsDemand();
        $demand->setSearch(null);
        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        self::assertEmpty($result);
    }

    /**
     * @test
     */
    public function constraintsAreReturnedForSearchSubject(): void
    {
        $stmt1 = new Statement('');
        $stmt2 = new Statement('');
        $propertyValue = new PropertyValue('dummy', '');
        $like = new Comparison($propertyValue, 1, '');
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();
        $mockedQuery->expects(self::any())->method('like')->willReturn($like);

        $or = GeneralUtility::makeInstance(LogicalOr::class, $stmt1, $stmt2);
        $mockedQuery->expects(self::any())->method('logicalOr')->willReturn($or);
        $search = new Search();
        $search->setSubject('Lorem');
        $search->setFields('title,fo');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        self::assertEquals(1, count($result));
    }

    /**
     * @test
     */
    public function constraintsAreReturnedForDateFields(): void
    {
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();

        $search = new Search();
        $search->setMinimumDate('2014-01-01');
        $search->setDateField('datetime');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        self::assertEquals(1, count($result));

        $search->setMaximumDate('2015-01-01');
        $demand->setSearch($search);

        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        self::assertEquals(2, count($result));

        $search->setMaximumDate('xyz');
        $demand->setSearch($search);

        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        self::assertEquals(1, count($result));
    }
}
