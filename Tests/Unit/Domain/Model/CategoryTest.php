<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\FileReference;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for \GeorgRinger\News\Domain\Model\Category
 */
class CategoryTest extends BaseTestCase
{
    /**
     * @var Category
     */
    protected $instance;

    protected function setUp(): void
    {
        $this->instance = new Category();
    }

    protected function tearDown(): void
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * @test
     */
    public function getSortingInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getSorting()
        );
    }

    /**
     * @test
     */
    public function setSortingWithStringConvertsValueToInt(): void
    {
        $this->instance->setSorting('123');
        self::assertSame(
            123,
            $this->instance->getSorting()
        );
    }

    /**
     * @test
     */
    public function setSortingWithIntSetsSorting(): void
    {
        $value = 123;
        $this->instance->setSorting($value);
        self::assertSame(
            $value,
            $this->instance->getSorting()
        );
    }

    /**
     * @test
     */
    public function getCrdateInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getCrdate()
        );
    }

    /**
     * @test
     */
    public function setCrdateSetsCrdate(): void
    {
        $date = new \DateTime('now');
        $this->instance->setCrdate($date);
        self::assertSame(
            $date,
            $this->instance->getCrdate()
        );
    }

    /**
     * @test
     */
    public function getTstampInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getTstamp()
        );
    }

    /**
     * @test
     */
    public function setTstampSetsTstamp(): void
    {
        $date = new \DateTime('now');
        $this->instance->setTstamp($date);
        self::assertSame(
            $date,
            $this->instance->getTstamp()
        );
    }

    /**
     * @test
     */
    public function getStarttimeInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getStarttime()
        );
    }

    /**
     * @test
     */
    public function setStarttimeSetsStarttime(): void
    {
        $date = new \DateTime('now');
        $this->instance->setStarttime($date);
        self::assertSame(
            $date,
            $this->instance->getStarttime()
        );
    }

    /**
     * @test
     */
    public function getEndtimeInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getEndtime()
        );
    }

    /**
     * @test
     */
    public function setEndtimeSetsEndtime(): void
    {
        $date = new \DateTime('now');
        $this->instance->setEndtime($date);
        self::assertSame(
            $date,
            $this->instance->getEndtime()
        );
    }

    /**
     * @test
     */
    public function getHiddenInitiallyReturnsFalse(): void
    {
        self::assertFalse(
            $this->instance->getHidden()
        );
    }

    /**
     * @test
     */
    public function setHiddenWithStringSetsHidden(): void
    {
        $this->instance->setHidden('1');
        self::assertTrue(
            $this->instance->getHidden()
        );
    }

    /**
     * @test
     */
    public function setHiddenWithIntSetsHidden(): void
    {
        $this->instance->setHidden(1);
        self::assertTrue(
            $this->instance->getHidden()
        );
    }

    /**
     * @test
     */
    public function setHiddenWithTrueSetsHidden(): void
    {
        $this->instance->setHidden(true);
        self::assertTrue(
            $this->instance->getHidden()
        );
    }

    /**
     * @test
     */
    public function setHiddenWithFalseSetsHidden(): void
    {
        $this->instance->setHidden(false);
        self::assertFalse(
            $this->instance->getHidden()
        );
    }

    /**
     * @test
     */
    public function getSysLanguageUidInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getSysLanguageUid()
        );
    }

    /**
     * @test
     */
    public function setSysLanguageUidWithStringConvertsToInt(): void
    {
        $this->instance->setSysLanguageUid('2');
        self::assertSame(
            2,
            $this->instance->getSysLanguageUid()
        );
    }

    /**
     * @test
     */
    public function setSysLanguageUidSetsSysLanguageUid(): void
    {
        $value = 3;
        $this->instance->setSysLanguageUid($value);
        self::assertSame(
            $value,
            $this->instance->getSysLanguageUid()
        );
    }

    /**
     * @test
     */
    public function getL10nParentInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getL10nParent()
        );
    }

    /**
     * @test
     */
    public function setL10nParentWithStringConvertsToInt(): void
    {
        $this->instance->setL10nParent('2');
        self::assertSame(
            2,
            $this->instance->getL10nParent()
        );
    }

    /**
     * @test
     */
    public function setL10nParentSetsL10nParent(): void
    {
        $value = 3;
        $this->instance->setL10nParent($value);
        self::assertSame(
            $value,
            $this->instance->getL10nParent()
        );
    }

    /**
     * @test
     */
    public function getTitleInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleWithIntConvertsToSting(): void
    {
        $this->instance->setTitle(123);
        self::assertSame(
            '123',
            $this->instance->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleSetsTitle(): void
    {
        $value = 'Hello';
        $this->instance->setTitle($value);
        self::assertSame(
            $value,
            $this->instance->getTitle()
        );
    }

    /**
     * @test
     */
    public function getDescriptionInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionWithIntConvertsToSting(): void
    {
        $this->instance->setDescription(123);
        self::assertSame(
            '123',
            $this->instance->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionSetsDescription(): void
    {
        $value = 'Hello';
        $this->instance->setDescription($value);
        self::assertSame(
            $value,
            $this->instance->getDescription()
        );
    }

    /**
     * @test
     */
    public function getParentcategoryInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getParentcategory()
        );
    }

    /**
     * @test
     */
    public function setParentcategorySetsParentcategory(): void
    {
        $value = new Category();
        $value->setTitle('TYPO3');
        $this->instance->setParentcategory($value);
        self::assertSame(
            $value,
            $this->instance->getParentcategory()
        );
    }

    /**
     * @test
     */
    public function getImagesInitiallyReturnsObjectStorage(): void
    {
        $value = new ObjectStorage();
        self::assertEquals(
            $value,
            $this->instance->getImages()
        );
    }

    /**
     * @test
     */
    public function setImagesSetsImages(): void
    {
        $value = new ObjectStorage();
        $this->instance->setImages($value);
        self::assertSame(
            $value,
            $this->instance->getImages()
        );
    }

    /**
     * @test
     */
    public function addImageAddsImage(): void
    {
        $fileReference = new FileReference();

        $images = new ObjectStorage();
        $images->attach($fileReference);

        $this->instance->addImage($fileReference);
        self::assertEquals(
            $images,
            $this->instance->getImages()
        );
    }

    /**
     * @test
     */
    public function removeImageRemovesImage(): void
    {
        $fileReference = new FileReference();

        $images = new ObjectStorage();
        $images->attach($fileReference);

        $expectedImages = clone $images;
        $expectedImages->detach($fileReference);

        $this->instance->setImages($images);

        $this->instance->removeImage($fileReference);
        self::assertEquals(
            $expectedImages,
            $this->instance->getImages()
        );
    }

    /**
     * @test
     */
    public function getFirstImageInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getFirstImage()
        );
    }

    /**
     * @test
     */
    public function getFirstImageReturnsFileReference(): void
    {
        $fileReference1 = new FileReference();
        $fileReference1->setTitle('Image 1');

        $fileReference2 = new FileReference();
        $fileReference2->setTitle('Image 2');

        $images = new ObjectStorage();
        $images->attach($fileReference1);
        $images->attach($fileReference2);

        $this->instance->setImages($images);

        self::assertSame(
            $fileReference1,
            $this->instance->getFirstImage()
        );
    }

    /**
     * @test
     */
    public function getShortcutInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getShortcut()
        );
    }

    /**
     * @test
     */
    public function setShortcutWithStringConvertsToInt(): void
    {
        $this->instance->setShortcut('43');
        self::assertSame(
            43,
            $this->instance->getShortcut()
        );
    }

    /**
     * @test
     */
    public function setShortcutSetsShortcut(): void
    {
        $value = 23;
        $this->instance->setShortcut($value);
        self::assertSame(
            $value,
            $this->instance->getShortcut()
        );
    }

    /**
     * @test
     */
    public function getSinglePidInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getSinglePid()
        );
    }

    /**
     * @test
     */
    public function setSinglePidWithStringConvertsToInt(): void
    {
        $this->instance->setSinglePid('43');
        self::assertSame(
            43,
            $this->instance->getSinglePid()
        );
    }

    /**
     * @test
     */
    public function setSinglePidSetsSinglePid(): void
    {
        $value = 23;
        $this->instance->setSinglePid($value);
        self::assertSame(
            $value,
            $this->instance->getSinglePid()
        );
    }

    /**
     * @test
     */
    public function getImportIdInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getImportId()
        );
    }

    /**
     * @test
     */
    public function setImportIdWithIntConvertsToString(): void
    {
        $this->instance->setImportId(24);
        self::assertSame(
            '24',
            $this->instance->getImportId()
        );
    }

    /**
     * @test
     */
    public function setImportIdSetsImportId(): void
    {
        $value = '1324';
        $this->instance->setImportId($value);
        self::assertSame(
            $value,
            $this->instance->getImportId()
        );
    }

    /**
     * @test
     */
    public function getImportSourceInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getImportSource()
        );
    }

    /**
     * @test
     */
    public function setImportSourceWithIntConvertsToString(): void
    {
        $this->instance->setImportSource(24);
        self::assertSame(
            '24',
            $this->instance->getImportSource()
        );
    }

    /**
     * @test
     */
    public function setImportSourceSetsImportSource(): void
    {
        $value = '1324';
        $this->instance->setImportSource($value);
        self::assertSame(
            $value,
            $this->instance->getImportSource()
        );
    }

    /**
     * @test
     */
    public function getFeGroupInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getImportSource()
        );
    }

    /**
     * @test
     */
    public function setFeGroupWithIntConvertsToString(): void
    {
        $this->instance->setFeGroup(24);
        self::assertSame(
            '24',
            $this->instance->getFeGroup()
        );
    }

    /**
     * @test
     */
    public function setFeGroupSetsFeGroup(): void
    {
        $value = '1324';
        $this->instance->setFeGroup($value);
        self::assertSame(
            $value,
            $this->instance->getFeGroup()
        );
    }

    /**
     * @test
     */
    public function getSeoTitleInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoTitle()
        );
    }

    /**
     * @test
     */
    public function setSeoTitleWithIntConvertsToString(): void
    {
        $this->instance->setSeoTitle(24);
        self::assertSame(
            '24',
            $this->instance->getSeoTitle()
        );
    }

    /**
     * @test
     */
    public function setSeoTitleSetsSeoTitle(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoTitle($value);
        self::assertSame(
            $value,
            $this->instance->getSeoTitle()
        );
    }

    /**
     * @test
     */
    public function getSeoDescriptionInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoDescription()
        );
    }

    /**
     * @test
     */
    public function setSeoDescriptionWithIntConvertsToString(): void
    {
        $this->instance->setSeoDescription(24);
        self::assertSame(
            '24',
            $this->instance->getSeoDescription()
        );
    }

    /**
     * @test
     */
    public function setSeoDescriptionSetsSeoDescription(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoDescription($value);
        self::assertSame(
            $value,
            $this->instance->getSeoDescription()
        );
    }

    /**
     * @test
     */
    public function getSeoHeadlineInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoHeadline()
        );
    }

    /**
     * @test
     */
    public function setSeoHeadlineWithIntConvertsToString(): void
    {
        $this->instance->setSeoHeadline(24);
        self::assertSame(
            '24',
            $this->instance->getSeoHeadline()
        );
    }

    /**
     * @test
     */
    public function setSeoHeadlineSetsSeoHeadline(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoHeadline($value);
        self::assertSame(
            $value,
            $this->instance->getSeoHeadline()
        );
    }

    /**
     * @test
     */
    public function getSeoTextInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoText()
        );
    }

    /**
     * @test
     */
    public function setSeoTextWithIntConvertsToString(): void
    {
        $this->instance->setSeoText(24);
        self::assertSame(
            '24',
            $this->instance->getSeoText()
        );
    }

    /**
     * @test
     */
    public function setSeoTextSetsSeoText(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoText($value);
        self::assertSame(
            $value,
            $this->instance->getSeoText()
        );
    }

    /**
     * @test
     */
    public function getSlugInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSlug()
        );
    }

    /**
     * @test
     */
    public function setSlugWithIntConvertsToString(): void
    {
        $this->instance->setSlug(24);
        self::assertSame(
            '24',
            $this->instance->getSlug()
        );
    }

    /**
     * @test
     */
    public function setSlugSetsSlug(): void
    {
        $value = 'TYPO3';
        $this->instance->setSlug($value);
        self::assertSame(
            $value,
            $this->instance->getSlug()
        );
    }
}
