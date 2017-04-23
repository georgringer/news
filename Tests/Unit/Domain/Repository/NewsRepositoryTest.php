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
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for domain repository newsRepository
 *
 *
 */
class NewsRepositoryTest extends UnitTestCase
{

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function getSearchConstraintsThrowsErrorIfNoSearchFieldIsGiven()
    {
        $mockedQuery = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface')->getMock();
        $mockedRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy'], [], '', false);

        $search = new Search();
        $search->setSubject('fo');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
    }
//
    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function getSearchConstraintsThrowsErrorIfNoDateFieldForMaximumDateIsGiven()
    {
        $mockedQuery = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface')->getMock();
        $mockedRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy'], [], '', false);

        $search = new Search();
        $search->setMaximumDate('2014-04-01');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
    }
//
    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function getSearchConstraintsThrowsErrorIfNoDateFieldForMinimumDateIsGiven()
    {
        $mockedQuery = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface')->getMock();
        $mockedRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy'], [], '', false);

        $search = new Search();
        $search->setMinimumDate('2014-04-01');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
    }
//
    /**
     * @test
     */
    public function emptyConstraintIsReturnedForEmptySearchDemand()
    {
        $mockedQuery = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface')->getMock();
        $mockedRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy'], [], '', false);

        $demand = new NewsDemand();
        $demand->setSearch(null);
        $result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function constraintsAreReturnedForSearchSubject()
    {
        $mockedQuery = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface')->getMock();
        $mockedRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy'], [], '', false);

        $search = new Search();
        $search->setSubject('Lorem');
        $search->setFields('title,fo');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEquals(1, count($result));
    }

    /**
     * @test
     */
    public function constraintsAreReturnedForDateFields()
    {
        $mockedQuery = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface')->getMock();
        $mockedRepository = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy'], [], '', false);

        $search = new Search();
        $search->setMinimumDate('2014-01-01');
        $search->setDateField('datetime');

        $demand = new NewsDemand();
        $demand->setSearch($search);

        $result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEquals(1, count($result));

        $search->setMaximumDate('2015-01-01');
        $demand->setSearch($search);

        $result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEquals(2, count($result));

        $search->setMaximumDate('xyz');
        $demand->setSearch($search);

        $result = $mockedRepository->_call('getSearchConstraints', $mockedQuery, $demand);
        $this->assertEquals(1, count($result));
    }
}
