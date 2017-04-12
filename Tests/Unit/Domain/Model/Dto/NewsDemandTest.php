<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

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
 * Tests for NewsDemand
 */
class NewsDemandTest extends UnitTestCase
{

    /** @var  NewsDemand */
    protected $instance;

    public function setup()
    {
        $this->instance = new NewsDemand();
    }

    /**
     * @test
     */
    public function categoriesCanBeSet()
    {
        $value = ['Test 123'];
        $this->instance->setCategories($value);
        $this->assertEquals($value, $this->instance->getCategories());
    }

    /**
     * @test
     */
    public function categoryConjunctionCanBeSet()
    {
        $value = 'AND';
        $this->instance->setCategoryConjunction($value);
        $this->assertEquals($value, $this->instance->getCategoryConjunction());
    }

    /**
     * @test
     */
    public function includeSubCategoriesCanBeSet()
    {
        $value = true;
        $this->instance->setIncludeSubCategories($value);
        $this->assertEquals($value, $this->instance->getIncludeSubCategories());
    }

    /**
     * @test
     */
    public function authorCanBeSet()
    {
        $value = '7elix';
        $this->instance->setAuthor($value);
        $this->assertEquals($value, $this->instance->getAuthor());
    }

    /**
     * @test
     */
    public function tagsCanBeSet()
    {
        $value = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->instance->setTags($value);
        $this->assertEquals($value, $this->instance->getTags());
    }

    /**
     * @test
     */
    public function archiveRestrictionCanBeSet()
    {
        $value = 'archive';
        $this->instance->setArchiveRestriction($value);
        $this->assertEquals($value, $this->instance->getArchiveRestriction());
    }

    /**
     * @test
     */
    public function timeRestrictionCanBeSet()
    {
        $value = '2014-04-01';
        $this->instance->setTimeRestriction($value);
        $this->assertEquals($value, $this->instance->getTimeRestriction());
    }

    /**
     * @test
     */
    public function timeRestrictionHighCanBeSet()
    {
        $value = '2014-05-01';
        $this->instance->setTimeRestrictionHigh($value);
        $this->assertEquals($value, $this->instance->getTimeRestrictionHigh());
    }

    /**
     * @test
     */
    public function topNewsRestrictionCanBeSet()
    {
        $value = 1;
        $this->instance->setTopNewsRestriction($value);
        $this->assertEquals($value, $this->instance->getTopNewsRestriction());
    }

    /**
     * @test
     */
    public function dateFieldCanBeSet()
    {
        $value = 'datetime';
        $this->instance->setDateField($value);
        $this->assertEquals($value, $this->instance->getDateField());

        $value = 'archive';
        $this->instance->setDateField($value);
        $this->assertEquals($value, $this->instance->getDateField());

        $value = 'invalid';
        $this->instance->setDateField($value);
        $this->assertEquals('', $this->instance->getDateField());
    }

    /**
     * @test
     */
    public function monthCanBeSet()
    {
        $value = 4;
        $this->instance->setMonth($value);
        $this->assertEquals($value, $this->instance->getMonth());
    }

    /**
     * @test
     */
    public function yearCanBeSet()
    {
        $value = 2014;
        $this->instance->setYear($value);
        $this->assertEquals($value, $this->instance->getYear());
    }

    /**
     * @test
     */
    public function dayCanBeSet()
    {
        $value = 1;
        $this->instance->setDay($value);
        $this->assertEquals($value, $this->instance->getDay());
    }

    /**
     * @test
     */
    public function searchFieldsCanBeSet()
    {
        $value = 'field1,field2';
        $this->instance->setSearchFields($value);
        $this->assertEquals($value, $this->instance->getSearchFields());
    }

    /**
     * @test
     */
    public function searchCanBeSet()
    {
        $value = new Search();
        $value->setSubject('fo');
        $this->instance->setSearch($value);
        $this->assertEquals($value, $this->instance->getSearch());
    }

    /**
     * @test
     */
    public function orderCanBeSet()
    {
        $value = 'order';
        $this->instance->setOrder($value);
        $this->assertEquals($value, $this->instance->getOrder());
    }

    /**
     * @test
     */
    public function orderByAllowedCanBeSet()
    {
        $value = 'order,order2';
        $this->instance->setOrderByAllowed($value);
        $this->assertEquals($value, $this->instance->getOrderByAllowed());
    }

    /**
     * @test
     */
    public function topNewsFirstCanBeSet()
    {
        $value = true;
        $this->instance->setTopNewsFirst($value);
        $this->assertEquals($value, $this->instance->getTopNewsFirst());
    }

    /**
     * @test
     */
    public function storagePageCanBeSet()
    {
        $value = 456;
        $this->instance->setStoragePage($value);
        $this->assertEquals($value, $this->instance->getStoragePage());
    }

    /**
     * @test
     */
    public function limitCanBeSet()
    {
        $value = 10;
        $this->instance->setLimit($value);
        $this->assertEquals($value, $this->instance->getLimit());
    }

    /**
     * @test
     */
    public function offsetCanBeSet()
    {
        $value = 20;
        $this->instance->setOffset($value);
        $this->assertEquals($value, $this->instance->getOffset());
    }

    /**
     * @test
     */
    public function excludeAlreadyDisplayedNewsCanBeSet()
    {
        $value = true;
        $this->instance->setExcludeAlreadyDisplayedNews($value);
        $this->assertEquals($value, $this->instance->getExcludeAlreadyDisplayedNews());
    }

    /**
     * @test
     */
    public function hideIdListCanBeSet()
    {
        $value = '123,456';
        $this->instance->setHideIdList($value);
        $this->assertEquals($value, $this->instance->getHideIdList());
    }

    /**
     * @test
     */
    public function actionCanBeSet()
    {
        $value = 'anAction';
        $this->instance->setAction($value);
        $this->assertEquals($value, $this->instance->getAction());
    }

    /**
     * @test
     */
    public function classCanBeSet()
    {
        $value = 'FooBar';
        $this->instance->setClass($value);
        $this->assertEquals($value, $this->instance->getClass());
    }

    /**
     * @test
     */
    public function typesCanBeSet()
    {
        $value = ['12', '34'];
        $this->instance->setTypes($value);
        $this->assertEquals($value, $this->instance->getTypes());
    }
}
