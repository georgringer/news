<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Repository;

/*
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
use GeorgRinger\News\Domain\Model\Dto\Search;

/**
 * Tests for domain repository newsRepository.
 */
class NewsRepositoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * Test create constraints from demand including topnews setting.
     *
     * @test
     *
     * @return void
     */
    public function createConstraintsFromDemandForDemandWithTopnewsSetting1QueriesForIsTopNews()
    {
        $demand = $this->getMock('GeorgRinger\\News\\Domain\\Model\\Dto\\NewsDemand');
        $demand->expects($this->atLeastOnce())->method('getTopNewsRestriction')
            ->will($this->returnValue(1));

        $query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\QueryInterface');
        $query->expects($this->once())->method('equals')->with('istopnews', 1);

        if (version_compare(TYPO3_branch, '6.2', '>=')) {
            $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
            $newsRepository = $this->getAccessibleMock(
                'GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy'], [$objectManager]
            );
        } else {
            $newsRepository = $this->getAccessibleMock(
                'GeorgRinger\\News\\Domain\\Repository\\NewsRepository', ['dummy']
            );
        }
        $newsRepository->_call('createConstraintsFromDemand', $query, $demand);
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function getSearchConstraintsThrowsErrorIfNoSearchFieldIsGiven()
    {
        $mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
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
        $mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
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
        $mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
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
        $mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
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
        $mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
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
        $mockedQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface');
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
