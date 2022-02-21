<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Dto\Search;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for domains model News
 *
 */
class SearchTest extends BaseTestCase
{

    /**
     * Test if subject can be set
     *
     * @test
     *
     * @return void
     */
    public function subjectCanBeSet(): void
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
     *
     * @return void
     */
    public function fieldsCanBeSet(): void
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
     *
     * @return void
     */
    public function minimumDateCanBeSet(): void
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
     *
     * @return void
     */
    public function maximumDateCanBeSet(): void
    {
        $domainModelInstance = new Search();
        $value = '456';
        $domainModelInstance->setMaximumDate($value);
        $this->assertEquals($value, $domainModelInstance->getMaximumDate());
    }
}
