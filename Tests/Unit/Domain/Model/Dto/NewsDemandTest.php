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
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for NewsDemand
 */
class NewsDemandTest extends BaseTestCase
{
    /** @var  NewsDemand */
    protected $instance;

    public function setup(): void
    {
        $this->instance = new NewsDemand();
    }

    /**
     * @test
     *
     * @return void
     */
    public function categoriesCanBeSet(): void
    {
        $value = ['Test 123'];
        $this->instance->setCategories($value);
        $this->assertEquals($value, $this->instance->getCategories());
    }

    /**
     * @test
     *
     * @return void
     */
    public function categoryConjunctionCanBeSet(): void
    {
        $value = 'AND';
        $this->instance->setCategoryConjunction($value);
        $this->assertEquals($value, $this->instance->getCategoryConjunction());
    }

    /**
     * @test
     *
     * @return void
     */
    public function includeSubCategoriesCanBeSet(): void
    {
        $value = true;
        $this->instance->setIncludeSubCategories($value);
        $this->assertEquals($value, $this->instance->getIncludeSubCategories());
    }

    /**
     * @test
     *
     * @return void
     */
    public function authorCanBeSet(): void
    {
        $value = '7elix';
        $this->instance->setAuthor($value);
        $this->assertEquals($value, $this->instance->getAuthor());
    }

    /**
     * @test
     *
     * @return void
     */
    public function tagsCanBeSet(): void
    {
        $value = '1,2,3';
        $this->instance->setTags($value);
        $this->assertEquals($value, $this->instance->getTags());
    }

    /**
     * @test
     *
     * @return void
     */
    public function archiveRestrictionCanBeSet(): void
    {
        $value = 'archive';
        $this->instance->setArchiveRestriction($value);
        $this->assertEquals($value, $this->instance->getArchiveRestriction());
    }

    /**
     * @test
     *
     * @return void
     */
    public function timeRestrictionCanBeSet(): void
    {
        $value = '2014-04-01';
        $this->instance->setTimeRestriction($value);
        $this->assertEquals($value, $this->instance->getTimeRestriction());
    }

    /**
     * @test
     *
     * @return void
     */
    public function timeRestrictionHighCanBeSet(): void
    {
        $value = '2014-05-01';
        $this->instance->setTimeRestrictionHigh($value);
        $this->assertEquals($value, $this->instance->getTimeRestrictionHigh());
    }

    /**
     * @test
     *
     * @return void
     */
    public function topNewsRestrictionCanBeSet(): void
    {
        $value = 1;
        $this->instance->setTopNewsRestriction($value);
        $this->assertEquals($value, $this->instance->getTopNewsRestriction());
    }

    /**
     * @test
     *
     * @return void
     */
    public function dateFieldCanBeSet(): void
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
     *
     * @return void
     */
    public function monthCanBeSet(): void
    {
        $value = 4;
        $this->instance->setMonth($value);
        $this->assertEquals($value, $this->instance->getMonth());
    }

    /**
     * @test
     *
     * @return void
     */
    public function yearCanBeSet(): void
    {
        $value = 2014;
        $this->instance->setYear($value);
        $this->assertEquals($value, $this->instance->getYear());
    }

    /**
     * @test
     *
     * @return void
     */
    public function dayCanBeSet(): void
    {
        $value = 1;
        $this->instance->setDay($value);
        $this->assertEquals($value, $this->instance->getDay());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchFieldsCanBeSet(): void
    {
        $value = 'field1,field2';
        $this->instance->setSearchFields($value);
        $this->assertEquals($value, $this->instance->getSearchFields());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchCanBeSet(): void
    {
        $value = new Search();
        $value->setSubject('fo');
        $this->instance->setSearch($value);
        $this->assertEquals($value, $this->instance->getSearch());
    }

    /**
     * @test
     *
     * @return void
     */
    public function orderCanBeSet(): void
    {
        $value = 'order';
        $this->instance->setOrder($value);
        $this->assertEquals($value, $this->instance->getOrder());
    }

    /**
     * @test
     *
     * @return void
     */
    public function orderByAllowedCanBeSet(): void
    {
        $value = 'order,order2';
        $this->instance->setOrderByAllowed($value);
        $this->assertEquals($value, $this->instance->getOrderByAllowed());
    }

    /**
     * @test
     *
     * @return void
     */
    public function topNewsFirstCanBeSet(): void
    {
        $value = true;
        $this->instance->setTopNewsFirst($value);
        $this->assertEquals($value, $this->instance->getTopNewsFirst());
    }

    /**
     * @test
     *
     * @return void
     */
    public function storagePageCanBeSet(): void
    {
        $value = 456;
        $this->instance->setStoragePage($value);
        $this->assertEquals($value, $this->instance->getStoragePage());
    }

    /**
     * @test
     *
     * @return void
     */
    public function limitCanBeSet(): void
    {
        $value = 10;
        $this->instance->setLimit($value);
        $this->assertEquals($value, $this->instance->getLimit());
    }

    /**
     * @test
     *
     * @return void
     */
    public function offsetCanBeSet(): void
    {
        $value = 20;
        $this->instance->setOffset($value);
        $this->assertEquals($value, $this->instance->getOffset());
    }

    /**
     * @test
     *
     * @return void
     */
    public function excludeAlreadyDisplayedNewsCanBeSet(): void
    {
        $value = true;
        $this->instance->setExcludeAlreadyDisplayedNews($value);
        $this->assertEquals($value, $this->instance->getExcludeAlreadyDisplayedNews());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hideIdListCanBeSet(): void
    {
        $value = '123,456';
        $this->instance->setHideIdList($value);
        $this->assertEquals($value, $this->instance->getHideIdList());
    }

    /**
     * @test
     *
     * @return void
     */
    public function actionCanBeSet(): void
    {
        $value = 'anAction';
        $this->instance->setAction($value);
        $this->assertEquals($value, $this->instance->getAction());
    }

    /**
     * @test
     *
     * @return void
     */
    public function classCanBeSet(): void
    {
        $value = 'FooBar';
        $this->instance->setClass($value);
        $this->assertEquals($value, $this->instance->getClass());
    }

    /**
     * @test
     *
     * @return void
     */
    public function typesCanBeSet(): void
    {
        $value = ['12', '34'];
        $this->instance->setTypes($value);
        $this->assertEquals($value, $this->instance->getTypes());
    }
}
