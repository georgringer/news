<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

/**
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
use GeorgRinger\News\Domain\Model\Dto\AdministrationDemand;

/**
 * Tests for AdministrationDemand
 *
 */
class AdministrationDemandTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
     */
    public function sortingDirectionCanBeSet()
    {
        $value = 'asc';
        $this->instance->setSortingDirection($value);
        $this->assertEquals($value, $this->instance->getSortingDirection());
    }
}
