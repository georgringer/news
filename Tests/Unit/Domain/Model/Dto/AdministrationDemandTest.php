<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Dto\AdministrationDemand;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for AdministrationDemand
 *
 */
class AdministrationDemandTest extends BaseTestCase
{

    /** @var  AdministrationDemand */
    protected $instance;

    public function setup(): void
    {
        $this->instance = new AdministrationDemand();
    }

    /**
     * Test if recursive can be set
     *
     * @test
     *
     * @return void
     */
    public function recursiveCanBeSet(): void
    {
        $value = 'Test 123';
        $this->instance->setRecursive($value);
        $this->assertEquals($value, $this->instance->getRecursive());
    }

    /**
     * Test if selectedCategories can be set
     *
     * @test
     *
     * @return void
     */
    public function selectedCategoriesCanBeSet(): void
    {
        $value = ['Test 123'];
        $this->instance->setCategories($value);
        $this->assertEquals($value, $this->instance->getCategories());
    }

    /**
     * Test if sortingField can be set
     *
     * @test
     *
     * @return void
     */
    public function sortingFieldCanBeSet(): void
    {
        $value = 'title';
        $this->instance->setSortingField($value);
        $this->assertEquals($value, $this->instance->getSortingField());
    }

    /**
     * Test if sortingDirection can be set
     *
     * @test
     *
     * @return void
     */
    public function sortingDirectionCanBeSet(): void
    {
        $value = 'asc';
        $this->instance->setSortingDirection($value);
        $this->assertEquals($value, $this->instance->getSortingDirection());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hiddenCanBeSet(): void
    {
        $value = 2;
        $this->instance->setHidden($value);
        $this->assertEquals($value, $this->instance->getHidden());
    }
}
