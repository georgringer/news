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
use GeorgRinger\News\Domain\Model\Dto\Search;

/**
 * Tests for domains model News
 *
 */
class SearchTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Test if subject can be set
     *
     * @test
     * @return void
     */
    public function subjectCanBeSet()
    {
        $domainModelInstance = new Search();
        $subject = 'Test 123';
        $domainModelInstance->setSubject($subject);
        $this->assertEquals($subject, $domainModelInstance->getSubject());
    }

    /**
     * Test if fields can be set
     *
     * @test
     * @return void
     */
    public function fieldsCanBeSet()
    {
        $domainModelInstance = new Search();
        $fields = 'field1,field2';
        $domainModelInstance->setFields($fields);
        $this->assertEquals($fields, $domainModelInstance->getFields());
    }

    /**
     * Test if minimumDate can be set
     *
     * @test
     * @return void
     */
    public function minimumDateCanBeSet()
    {
        $domainModelInstance = new Search();
        $value = '123';
        $domainModelInstance->setMinimumDate($value);
        $this->assertEquals($value, $domainModelInstance->getMinimumDate());
    }

    /**
     * Test if minimumDate can be set
     *
     * @test
     * @return void
     */
    public function maximumDateCanBeSet()
    {
        $domainModelInstance = new Search();
        $value = '456';
        $domainModelInstance->setMaximumDate($value);
        $this->assertEquals($value, $domainModelInstance->getMaximumDate());
    }
}
