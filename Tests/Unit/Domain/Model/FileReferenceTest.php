<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\FileReference;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for GeorgRinger\News\Domain\Model\FileReference
 */
class FileReferenceTest extends BaseTestCase
{
    /**
     * Test if fileUid can be set
     *
     * @test
     *
     * @return void
     */
    public function fileUidCanBeSet(): void
    {
        $domainModelInstance = new FileReference();
        $value = 781;
        $domainModelInstance->setFileUid($value);
        $this->assertEquals($value, $domainModelInstance->getFileUid());
    }

    /**
     * Test if alternative can be set
     *
     * @test
     *
     * @return void
     */
    public function alternativeBeSet(): void
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
     *
     * @return void
     */
    public function descriptionBeSet(): void
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
     *
     * @return void
     */
    public function linkBeSet(): void
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
     *
     * @return void
     */
    public function titleBeSet(): void
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
     *
     * @return void
     */
    public function showInPreviewBeSet(): void
    {
        $domainModelInstance = new FileReference();
        $value = 2;
        $domainModelInstance->setShowinpreview($value);
        $this->assertEquals($value, $domainModelInstance->getShowinpreview());
    }
}
