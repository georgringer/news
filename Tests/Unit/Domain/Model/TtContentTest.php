<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

use GeorgRinger\News\Domain\Model\TtContent;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for tt_content model
 */
class TtContentTest extends BaseTestCase
{
    /**
     * @var TtContent
     */
    protected $ttContentDomainModelInstance;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->ttContentDomainModelInstance = new TtContent();
    }

    /**
     * @test
     */
    public function crdateCanBeSet(): void
    {
        $fieldValue = new \DateTime();
        $this->ttContentDomainModelInstance->setCrdate($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCrdate());
    }

    /**
     * @test
     */
    public function tstampCanBeSet(): void
    {
        $fieldValue = new \DateTime();
        $this->ttContentDomainModelInstance->setTstamp($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTstamp());
    }

    /**
     * @test
     */
    public function cTypeCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setCType($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCType());
    }

    /**
     * @test
     */
    public function headerCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeader($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeader());
    }

    /**
     * @test
     */
    public function headerPositionCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeaderPosition($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderPosition());
    }

    /**
     * @test
     */
    public function bodytextCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setBodytext($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getBodytext());
    }

    /**
     * @test
     */
    public function colPosCanBeSet(): void
    {
        $fieldValue = 1;
        $this->ttContentDomainModelInstance->setColPos($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getColPos());
    }

    /**
     * @test
     */
    public function imageCanBeSet(): void
    {
        $imageItem = new FileReference();

        $image = new ObjectStorage();
        $image->attach($imageItem);

        $this->ttContentDomainModelInstance->setImage($image);
        self::assertEquals($image, $this->ttContentDomainModelInstance->getImage());
    }

    /**
     * @test
     */
    public function imageCanBeAdded(): void
    {
        $imageItem = new FileReference();

        $ttContent = new TtContent();
        $ttContent->addImage($imageItem);

        self::assertEquals($ttContent->getImage()->current(), $imageItem);
    }

    /**
     * @test
     */
    public function imageWidthCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagewidth($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagewidth());
    }

    /**
     * @test
     */
    public function imageOrientCanBeSet(): void
    {
        $fieldValue = 3;
        $this->ttContentDomainModelInstance->setImageorient($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageorient());
    }

    /**
     * @test
     */
    public function imageCaptionCanBeSet(): void
    {
        $fieldValue = 'Test123';
        $this->ttContentDomainModelInstance->setImagecaption($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecaption());
    }

    /**
     * @test
     */
    public function imageColsCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagecols($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecols());
    }

    /**
     * @test
     */
    public function imageBorderCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImageborder($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageborder());
    }

    /**
     * @test
     */
    public function mediaCanBeSet(): void
    {
        $mediaItem = new FileReference();

        $media = new ObjectStorage();
        $media->attach($mediaItem);

        $this->ttContentDomainModelInstance->setMedia($media);
        self::assertEquals($media, $this->ttContentDomainModelInstance->getMedia());
    }

    /**
     * @test
     */
    public function mediaCanBeAdded(): void
    {
        $mediaItem = new FileReference();

        $ttContent = new TtContent();
        $ttContent->addMedia($mediaItem);

        self::assertEquals($ttContent->getMedia()->current(), $mediaItem);
    }

    /**
     * @test
     */
    public function layoutCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setLayout($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getLayout());
    }

    /**
     * @test
     */
    public function colsCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setCols($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCols());
    }

    /**
     * @test
     */
    public function subheaderCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setSubheader($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getSubheader());
    }

    /**
     * @test
     */
    public function headerLinkCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLink($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLink());
    }

    /**
     * @test
     */
    public function imageLinkCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageLink($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageLink());
    }

    /**
     * @test
     */
    public function imageZoomCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageZoom($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageZoom());
    }

    /**
     * @test
     */
    public function altTextCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setAltText($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getAltText());
    }

    /**
     * @test
     */
    public function titleTextCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setTitleText($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTitleText());
    }

    /**
     * @test
     */
    public function headerLayoutCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLayout($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLayout());
    }

    /**
     * @test
     */
    public function listTypeCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setListType($fieldValue);
        self::assertEquals($fieldValue, $this->ttContentDomainModelInstance->getListType());
    }
}
