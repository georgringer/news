<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

use DateTime;
use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\FileReference;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for \GeorgRinger\News\Domain\Model\Category
 */
class CategoryTest extends BaseTestCase
{
    /** @var Category */
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

    #[Test]
    public function getSortingInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getSorting()
        );
    }

    #[Test]
    public function setSortingWithStringConvertsValueToInt(): void
    {
        $this->instance->setSorting('123');
        self::assertSame(
            123,
            $this->instance->getSorting()
        );
    }

    #[Test]
    public function setSortingWithIntSetsSorting(): void
    {
        $value = 123;
        $this->instance->setSorting($value);
        self::assertSame(
            $value,
            $this->instance->getSorting()
        );
    }

    #[Test]
    public function getCrdateInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getCrdate()
        );
    }

    #[Test]
    public function setCrdateSetsCrdate(): void
    {
        $date = new DateTime('now');
        $this->instance->setCrdate($date);
        self::assertSame(
            $date,
            $this->instance->getCrdate()
        );
    }

    #[Test]
    public function getTstampInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getTstamp()
        );
    }

    #[Test]
    public function setTstampSetsTstamp(): void
    {
        $date = new DateTime('now');
        $this->instance->setTstamp($date);
        self::assertSame(
            $date,
            $this->instance->getTstamp()
        );
    }

    #[Test]
    public function getStarttimeInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getStarttime()
        );
    }

    #[Test]
    public function setStarttimeSetsStarttime(): void
    {
        $date = new DateTime('now');
        $this->instance->setStarttime($date);
        self::assertSame(
            $date,
            $this->instance->getStarttime()
        );
    }

    #[Test]
    public function getEndtimeInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getEndtime()
        );
    }

    #[Test]
    public function setEndtimeSetsEndtime(): void
    {
        $date = new DateTime('now');
        $this->instance->setEndtime($date);
        self::assertSame(
            $date,
            $this->instance->getEndtime()
        );
    }

    #[Test]
    public function getHiddenInitiallyReturnsFalse(): void
    {
        self::assertFalse(
            $this->instance->getHidden()
        );
    }

    #[Test]
    public function setHiddenWithStringSetsHidden(): void
    {
        $this->instance->setHidden('1');
        self::assertTrue(
            $this->instance->getHidden()
        );
    }

    #[Test]
    public function setHiddenWithIntSetsHidden(): void
    {
        $this->instance->setHidden(1);
        self::assertTrue(
            $this->instance->getHidden()
        );
    }

    #[Test]
    public function setHiddenWithTrueSetsHidden(): void
    {
        $this->instance->setHidden(true);
        self::assertTrue(
            $this->instance->getHidden()
        );
    }

    #[Test]
    public function setHiddenWithFalseSetsHidden(): void
    {
        $this->instance->setHidden(false);
        self::assertFalse(
            $this->instance->getHidden()
        );
    }

    #[Test]
    public function getSysLanguageUidInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getSysLanguageUid()
        );
    }

    #[Test]
    public function setSysLanguageUidWithStringConvertsToInt(): void
    {
        $this->instance->setSysLanguageUid('2');
        self::assertSame(
            2,
            $this->instance->getSysLanguageUid()
        );
    }

    #[Test]
    public function setSysLanguageUidSetsSysLanguageUid(): void
    {
        $value = 3;
        $this->instance->setSysLanguageUid($value);
        self::assertSame(
            $value,
            $this->instance->getSysLanguageUid()
        );
    }

    #[Test]
    public function getL10nParentInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getL10nParent()
        );
    }

    #[Test]
    public function setL10nParentWithStringConvertsToInt(): void
    {
        $this->instance->setL10nParent('2');
        self::assertSame(
            2,
            $this->instance->getL10nParent()
        );
    }

    #[Test]
    public function setL10nParentSetsL10nParent(): void
    {
        $value = 3;
        $this->instance->setL10nParent($value);
        self::assertSame(
            $value,
            $this->instance->getL10nParent()
        );
    }

    #[Test]
    public function getTitleInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getTitle()
        );
    }

    #[Test]
    public function setTitleWithIntConvertsToSting(): void
    {
        $this->instance->setTitle(123);
        self::assertSame(
            '123',
            $this->instance->getTitle()
        );
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $value = 'Hello';
        $this->instance->setTitle($value);
        self::assertSame(
            $value,
            $this->instance->getTitle()
        );
    }

    #[Test]
    public function getDescriptionInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getDescription()
        );
    }

    #[Test]
    public function setDescriptionWithIntConvertsToSting(): void
    {
        $this->instance->setDescription(123);
        self::assertSame(
            '123',
            $this->instance->getDescription()
        );
    }

    #[Test]
    public function setDescriptionSetsDescription(): void
    {
        $value = 'Hello';
        $this->instance->setDescription($value);
        self::assertSame(
            $value,
            $this->instance->getDescription()
        );
    }

    #[Test]
    public function getParentcategoryInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getParentcategory()
        );
    }

    #[Test]
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

    #[Test]
    public function getImagesInitiallyReturnsObjectStorage(): void
    {
        $value = new ObjectStorage();
        self::assertEquals(
            $value,
            $this->instance->getImages()
        );
    }

    #[Test]
    public function setImagesSetsImages(): void
    {
        $value = new ObjectStorage();
        $this->instance->setImages($value);
        self::assertSame(
            $value,
            $this->instance->getImages()
        );
    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function getFirstImageInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->instance->getFirstImage()
        );
    }

    #[Test]
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

    #[Test]
    public function getShortcutInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getShortcut()
        );
    }

    #[Test]
    public function setShortcutWithStringConvertsToInt(): void
    {
        $this->instance->setShortcut('43');
        self::assertSame(
            43,
            $this->instance->getShortcut()
        );
    }

    #[Test]
    public function setShortcutSetsShortcut(): void
    {
        $value = 23;
        $this->instance->setShortcut($value);
        self::assertSame(
            $value,
            $this->instance->getShortcut()
        );
    }

    #[Test]
    public function getSinglePidInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->instance->getSinglePid()
        );
    }

    #[Test]
    public function setSinglePidWithStringConvertsToInt(): void
    {
        $this->instance->setSinglePid('43');
        self::assertSame(
            43,
            $this->instance->getSinglePid()
        );
    }

    #[Test]
    public function setSinglePidSetsSinglePid(): void
    {
        $value = 23;
        $this->instance->setSinglePid($value);
        self::assertSame(
            $value,
            $this->instance->getSinglePid()
        );
    }

    #[Test]
    public function getImportIdInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getImportId()
        );
    }

    #[Test]
    public function setImportIdWithIntConvertsToString(): void
    {
        $this->instance->setImportId(24);
        self::assertSame(
            '24',
            $this->instance->getImportId()
        );
    }

    #[Test]
    public function setImportIdSetsImportId(): void
    {
        $value = '1324';
        $this->instance->setImportId($value);
        self::assertSame(
            $value,
            $this->instance->getImportId()
        );
    }

    #[Test]
    public function getImportSourceInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getImportSource()
        );
    }

    #[Test]
    public function setImportSourceWithIntConvertsToString(): void
    {
        $this->instance->setImportSource(24);
        self::assertSame(
            '24',
            $this->instance->getImportSource()
        );
    }

    #[Test]
    public function setImportSourceSetsImportSource(): void
    {
        $value = '1324';
        $this->instance->setImportSource($value);
        self::assertSame(
            $value,
            $this->instance->getImportSource()
        );
    }

    #[Test]
    public function getFeGroupInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getImportSource()
        );
    }

    #[Test]
    public function setFeGroupWithIntConvertsToString(): void
    {
        $this->instance->setFeGroup(24);
        self::assertSame(
            '24',
            $this->instance->getFeGroup()
        );
    }

    #[Test]
    public function setFeGroupSetsFeGroup(): void
    {
        $value = '1324';
        $this->instance->setFeGroup($value);
        self::assertSame(
            $value,
            $this->instance->getFeGroup()
        );
    }

    #[Test]
    public function getSeoTitleInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoTitle()
        );
    }

    #[Test]
    public function setSeoTitleWithIntConvertsToString(): void
    {
        $this->instance->setSeoTitle(24);
        self::assertSame(
            '24',
            $this->instance->getSeoTitle()
        );
    }

    #[Test]
    public function setSeoTitleSetsSeoTitle(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoTitle($value);
        self::assertSame(
            $value,
            $this->instance->getSeoTitle()
        );
    }

    #[Test]
    public function getSeoDescriptionInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoDescription()
        );
    }

    #[Test]
    public function setSeoDescriptionWithIntConvertsToString(): void
    {
        $this->instance->setSeoDescription(24);
        self::assertSame(
            '24',
            $this->instance->getSeoDescription()
        );
    }

    #[Test]
    public function setSeoDescriptionSetsSeoDescription(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoDescription($value);
        self::assertSame(
            $value,
            $this->instance->getSeoDescription()
        );
    }

    #[Test]
    public function getSeoHeadlineInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoHeadline()
        );
    }

    #[Test]
    public function setSeoHeadlineWithIntConvertsToString(): void
    {
        $this->instance->setSeoHeadline(24);
        self::assertSame(
            '24',
            $this->instance->getSeoHeadline()
        );
    }

    #[Test]
    public function setSeoHeadlineSetsSeoHeadline(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoHeadline($value);
        self::assertSame(
            $value,
            $this->instance->getSeoHeadline()
        );
    }

    #[Test]
    public function getSeoTextInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSeoText()
        );
    }

    #[Test]
    public function setSeoTextWithIntConvertsToString(): void
    {
        $this->instance->setSeoText(24);
        self::assertSame(
            '24',
            $this->instance->getSeoText()
        );
    }

    #[Test]
    public function setSeoTextSetsSeoText(): void
    {
        $value = 'TYPO3';
        $this->instance->setSeoText($value);
        self::assertSame(
            $value,
            $this->instance->getSeoText()
        );
    }

    #[Test]
    public function getSlugInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->instance->getSlug()
        );
    }

    #[Test]
    public function setSlugWithIntConvertsToString(): void
    {
        $this->instance->setSlug(24);
        self::assertSame(
            '24',
            $this->instance->getSlug()
        );
    }

    #[Test]
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
