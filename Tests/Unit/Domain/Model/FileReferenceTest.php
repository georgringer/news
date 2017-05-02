<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\FileReference;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for GeorgRinger\News\Domain\Model\FileReference
 */
class FileReferenceTest extends UnitTestCase
{

    /**
     * Test if fileUid can be set
     *
     * @test
     */
    public function fileUidCanBeSet()
    {
        $domainModelInstance = new FileReference();
        $value = 'Test 123';
        $domainModelInstance->setFileUid($value);
        $this->assertEquals($value, $domainModelInstance->getFileUid());
    }

    /**
     * Test if alternative can be set
     *
     * @test
     */
    public function alternativeBeSet()
    {
        $domainModelInstance = new FileReference();
        $value = 'Test 123';
        $domainModelInstance->setAlternative($value);
        $this->assertEquals($value, $domainModelInstance->getAlternative());
    }

    /**
     * Test if description can be set
     *
     * @test
     */
    public function descriptionBeSet()
    {
        $domainModelInstance = new FileReference();
        $value = 'Test 123';
        $domainModelInstance->setDescription($value);
        $this->assertEquals($value, $domainModelInstance->getDescription());
    }

    /**
     * Test if link can be set
     *
     * @test
     */
    public function linkBeSet()
    {
        $domainModelInstance = new FileReference();
        $value = 'Test 123';
        $domainModelInstance->setLink($value);
        $this->assertEquals($value, $domainModelInstance->getLink());
    }

    /**
     * Test if title can be set
     *
     * @test
     */
    public function titleBeSet()
    {
        $domainModelInstance = new FileReference();
        $value = 'Test 123';
        $domainModelInstance->setTitle($value);
        $this->assertEquals($value, $domainModelInstance->getTitle());
    }

    /**
     * Test if showInPreview can be set
     *
     * @test
     */
    public function showInPreviewBeSet()
    {
        $domainModelInstance = new FileReference();
        $value = true;
        $domainModelInstance->setShowinpreview($value);
        $this->assertEquals($value, $domainModelInstance->getShowinpreview());
    }
}
