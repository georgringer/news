<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\Test;
use DateTime;
use GeorgRinger\News\Domain\Model\TtContent;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for tt_content model
 */
class TtContentTest extends BaseTestCase
{
    /** @var TtContent */
    protected $ttContentDomainModelInstance;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->ttContentDomainModelInstance = new TtContent();
    }

    #[Test]
    public function crdateCanBeSet(): void
    {
        $fieldValue = new DateTime();
        $this->ttContentDomainModelInstance->setCrdate($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCrdate());
    }

    #[Test]
    public function tstampCanBeSet(): void
    {
        $fieldValue = new DateTime();
        $this->ttContentDomainModelInstance->setTstamp($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTstamp());
    }

    #[Test]
    public function cTypeCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setCType($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCType());
    }

    #[Test]
    public function headerCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeader($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeader());
    }

    #[Test]
    public function headerPositionCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeaderPosition($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderPosition());
    }

    #[Test]
    public function bodytextCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setBodytext($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getBodytext());
    }

    #[Test]
    public function colPosCanBeSet(): void
    {
        $fieldValue = 1;
        $this->ttContentDomainModelInstance->setColPos($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getColPos());
    }

    #[Test]
    public function imageCanBeSet(): void
    {
        $imageItem = new FileReference();

        $image = new ObjectStorage();
        $image->attach($imageItem);

        $this->ttContentDomainModelInstance->setImage($image);
        self::assertEquals($image, $this->ttContentDomainModelInstance->getImage());
    }

    #[Test]
    public function imageCanBeAdded(): void
    {
        $imageItem = new FileReference();

        $ttContent = new TtContent();
        $ttContent->addImage($imageItem);

        self::assertEquals($ttContent->getImage()->current(), $imageItem);
    }

    #[Test]
    public function imageWidthCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagewidth($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagewidth());
    }

    #[Test]
    public function imageOrientCanBeSet(): void
    {
        $fieldValue = 3;
        $this->ttContentDomainModelInstance->setImageorient($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageorient());
    }

    #[Test]
    public function imageCaptionCanBeSet(): void
    {
        $fieldValue = 'Test123';
        $this->ttContentDomainModelInstance->setImagecaption($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecaption());
    }

    #[Test]
    public function imageColsCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagecols($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecols());
    }

    #[Test]
    public function imageBorderCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImageborder($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageborder());
    }

    #[Test]
    public function mediaCanBeSet(): void
    {
        $mediaItem = new FileReference();

        $media = new ObjectStorage();
        $media->attach($mediaItem);

        $this->ttContentDomainModelInstance->setMedia($media);
        self::assertEquals($media, $this->ttContentDomainModelInstance->getMedia());
    }

    #[Test]
    public function mediaCanBeAdded(): void
    {
        $mediaItem = new FileReference();

        $ttContent = new TtContent();
        $ttContent->addMedia($mediaItem);

        self::assertEquals($ttContent->getMedia()->current(), $mediaItem);
    }

    #[Test]
    public function layoutCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setLayout($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getLayout());
    }

    #[Test]
    public function colsCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setCols($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCols());
    }

    #[Test]
    public function subheaderCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setSubheader($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getSubheader());
    }

    #[Test]
    public function headerLinkCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLink($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLink());
    }

    #[Test]
    public function imageLinkCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageLink($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageLink());
    }

    #[Test]
    public function imageZoomCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageZoom($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageZoom());
    }

    #[Test]
    public function altTextCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setAltText($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getAltText());
    }

    #[Test]
    public function titleTextCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setTitleText($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTitleText());
    }

    #[Test]
    public function headerLayoutCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLayout($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLayout());
    }

    #[Test]
    public function listTypeCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setListType($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getListType());
    }
}
