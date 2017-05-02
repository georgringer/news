<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model\Dto;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Dto\Search;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for domains model News
 *
 */
class SearchTest extends UnitTestCase
{

    /**
     * Test if subject can be set
     *
     * @test
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
     */
    public function maximumDateCanBeSet()
    {
        $domainModelInstance = new Search();
        $value = '456';
        $domainModelInstance->setMaximumDate($value);
        $this->assertEquals($value, $domainModelInstance->getMaximumDate());
    }
}
