<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Dto\AdministrationDemand;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for AdministrationDemand
 *
 */
class AdministrationDemandTest extends UnitTestCase
{

    /** @var  AdministrationDemand */
    protected $instance;

    public function setup()
    {
        $this->instance = new AdministrationDemand();
    }

    /**
     * Test if recursive can be set
     *
     * @test
     */
    public function recursiveCanBeSet()
    {
        $value = 'Test 123';
        $this->instance->setRecursive($value);
        $this->assertEquals($value, $this->instance->getRecursive());
    }

    /**
     * Test if selectedCategories can be set
     *
     * @test
     */
    public function selectedCategoriesCanBeSet()
    {
        $value = ['Test 123'];
        $this->instance->setCategories($value);
        $this->assertEquals($value, $this->instance->getCategories());
    }

    /**
     * Test if sortingField can be set
     *
     * @test
     */
    public function sortingFieldCanBeSet()
    {
        $value = 'title';
        $this->instance->setSortingField($value);
        $this->assertEquals($value, $this->instance->getSortingField());
    }

    /**
     * Test if sortingDirection can be set
     *
     * @test
     */
    public function sortingDirectionCanBeSet()
    {
        $value = 'asc';
        $this->instance->setSortingDirection($value);
        $this->assertEquals($value, $this->instance->getSortingDirection());
    }

    /**
     * @test
     */
    public function hiddenCanBeSet()
    {
        $value = 2;
        $this->instance->setHidden($value);
        $this->assertEquals($value, $this->instance->getHidden());
    }
}
