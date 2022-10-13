<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Model\Dto\Search;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\TestingFramework\Core\BaseTestCase;
use UnexpectedValueException;

/**
 * Tests for domain repository newsRepository
 *
 *
 */
class NewsRepositoryTest extends BaseTestCase
{
    /** @var \GeorgRinger\News\Domain\Repository\NewsRepository|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface */
    protected $mockedNewsRepository;

    public function setup(): void
    {
        $this->mockedNewsRepository = $this->getAccessibleMock(NewsRepository::class, ['getQueryBuilder'], [], '', false);

        $mockedQueryBuilder = $this->getAccessibleMock(QueryBuilder::class, ['escapeStrForLike', 'createNamedParameter'], [], '', false);
        $this->mockedNewsRepository->expects($this->any())->method('getQueryBuilder')->withAnyParameters()->will($this->returnValue($mockedQueryBuilder));
    }

    /**
     * @test
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
     */
    public function emptyConstraintIsReturnedForEmptySearchDemand(): void
    {
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();

        $demand = new NewsDemand();
        $demand->setSearch(null);
        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEmpty($result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function constraintsAreReturnedForSearchSubject(): void
    {
        $mockedQuery = $this->getMockBuilder(QueryInterface::class)->getMock();

        $search = new Search();
        $search->setSubject('Lorem');
        $search->setFields('title,fo');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEquals(1, count($result));
    }

    /**
     * @test
     *
     * @return void
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
        $this->assertEquals(1, count($result));

        $search->setMaximumDate('2015-01-01');
        $demand->setSearch($search);

        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEquals(2, count($result));

        $search->setMaximumDate('xyz');
        $demand->setSearch($search);

        $result = $this->mockedNewsRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEquals(1, count($result));
    }
}
