<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Model\Dto\Search;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    public function categoriesCanBeSet(): void
    {
        $value = ['Test 123'];
        $this->instance->setCategories($value);
        self::assertEquals($value, $this->instance->getCategories());
    }

    #[Test]
    public function categoryConjunctionCanBeSet(): void
    {
        $value = 'AND';
        $this->instance->setCategoryConjunction($value);
        self::assertEquals($value, $this->instance->getCategoryConjunction());
    }

    #[Test]
    public function includeSubCategoriesCanBeSet(): void
    {
        $value = true;
        $this->instance->setIncludeSubCategories($value);
        self::assertEquals($value, $this->instance->getIncludeSubCategories());
    }

    #[Test]
    public function authorCanBeSet(): void
    {
        $value = '7elix';
        $this->instance->setAuthor($value);
        self::assertEquals($value, $this->instance->getAuthor());
    }

    #[Test]
    public function tagsCanBeSet(): void
    {
        $value = '1,2,3';
        $this->instance->setTags($value);
        self::assertEquals($value, $this->instance->getTags());
    }

    #[Test]
    public function archiveRestrictionCanBeSet(): void
    {
        $value = 'archive';
        $this->instance->setArchiveRestriction($value);
        self::assertEquals($value, $this->instance->getArchiveRestriction());
    }

    #[Test]
    public function timeRestrictionCanBeSet(): void
    {
        $value = '2014-04-01';
        $this->instance->setTimeRestriction($value);
        self::assertEquals($value, $this->instance->getTimeRestriction());
    }

    #[Test]
    public function timeRestrictionHighCanBeSet(): void
    {
        $value = '2014-05-01';
        $this->instance->setTimeRestrictionHigh($value);
        self::assertEquals($value, $this->instance->getTimeRestrictionHigh());
    }

    #[Test]
    public function topNewsRestrictionCanBeSet(): void
    {
        $value = 1;
        $this->instance->setTopNewsRestriction($value);
        self::assertEquals($value, $this->instance->getTopNewsRestriction());
    }

    #[Test]
    public function dateFieldCanBeSet(): void
    {
        $value = 'datetime';
        $this->instance->setDateField($value);
        self::assertEquals($value, $this->instance->getDateField());

        $value = 'archive';
        $this->instance->setDateField($value);
        self::assertEquals($value, $this->instance->getDateField());

        $value = 'invalid';
        $this->instance->setDateField($value);
        self::assertEquals('', $this->instance->getDateField());
    }

    #[Test]
    public function monthCanBeSet(): void
    {
        $value = 4;
        $this->instance->setMonth($value);
        self::assertEquals($value, $this->instance->getMonth());
    }

    #[Test]
    public function yearCanBeSet(): void
    {
        $value = 2014;
        $this->instance->setYear($value);
        self::assertEquals($value, $this->instance->getYear());
    }

    #[Test]
    public function dayCanBeSet(): void
    {
        $value = 1;
        $this->instance->setDay($value);
        self::assertEquals($value, $this->instance->getDay());
    }

    #[Test]
    public function searchFieldsCanBeSet(): void
    {
        $value = 'field1,field2';
        $this->instance->setSearchFields($value);
        self::assertEquals($value, $this->instance->getSearchFields());
    }

    #[Test]
    public function searchCanBeSet(): void
    {
        $value = new Search();
        $value->setSubject('fo');
        $this->instance->setSearch($value);
        self::assertEquals($value, $this->instance->getSearch());
    }

    #[Test]
    public function orderCanBeSet(): void
    {
        $value = 'order';
        $this->instance->setOrder($value);
        self::assertEquals($value, $this->instance->getOrder());
    }

    #[Test]
    public function orderByAllowedCanBeSet(): void
    {
        $value = 'order,order2';
        $this->instance->setOrderByAllowed($value);
        self::assertEquals($value, $this->instance->getOrderByAllowed());
    }

    #[Test]
    public function topNewsFirstCanBeSet(): void
    {
        $value = true;
        $this->instance->setTopNewsFirst($value);
        self::assertEquals($value, $this->instance->getTopNewsFirst());
    }

    #[Test]
    public function storagePageCanBeSet(): void
    {
        $value = 456;
        $this->instance->setStoragePage($value);
        self::assertEquals($value, $this->instance->getStoragePage());
    }

    #[Test]
    public function limitCanBeSet(): void
    {
        $value = 10;
        $this->instance->setLimit($value);
        self::assertEquals($value, $this->instance->getLimit());
    }

    #[Test]
    public function offsetCanBeSet(): void
    {
        $value = 20;
        $this->instance->setOffset($value);
        self::assertEquals($value, $this->instance->getOffset());
    }

    #[Test]
    public function excludeAlreadyDisplayedNewsCanBeSet(): void
    {
        $value = true;
        $this->instance->setExcludeAlreadyDisplayedNews($value);
        self::assertEquals($value, $this->instance->getExcludeAlreadyDisplayedNews());
    }

    #[Test]
    public function hideIdListCanBeSet(): void
    {
        $value = '123,456';
        $this->instance->setHideIdList($value);
        self::assertEquals($value, $this->instance->getHideIdList());
    }

    #[Test]
    public function actionCanBeSet(): void
    {
        $value = 'anAction';
        $this->instance->setAction($value);
        self::assertEquals($value, $this->instance->getAction());
    }

    #[Test]
    public function classCanBeSet(): void
    {
        $value = 'FooBar';
        $this->instance->setClass($value);
        self::assertEquals($value, $this->instance->getClass());
    }

    #[Test]
    public function typesCanBeSet(): void
    {
        $value = ['12', '34'];
        $this->instance->setTypes($value);
        self::assertEquals($value, $this->instance->getTypes());
    }
}
