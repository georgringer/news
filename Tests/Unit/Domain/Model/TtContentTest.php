<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\TtContent;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for tt_content model
 *
 */
class TtContentTest extends BaseTestCase
{
    /**
     * @var TtContent
     */
    protected $ttContentDomainModelInstance;

    /**
     * Setup
     *
     */
    protected function setUp(): void
    {
        $this->ttContentDomainModelInstance = new TtContent();
    }

    /**
     * @test
     *
     * @return void
     */
    public function crdateCanBeSet(): void
    {
        $fieldValue = new \DateTime();
        $this->ttContentDomainModelInstance->setCrdate($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCrdate());
    }

    /**
     * @test
     *
     * @return void
     */
    public function tstampCanBeSet(): void
    {
        $fieldValue = new \DateTime();
        $this->ttContentDomainModelInstance->setTstamp($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTstamp());
    }

    /**
     * @test
     *
     * @return void
     */
    public function cTypeCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setCType($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCType());
    }

    /**
     * @test
     *
     * @return void
     */
    public function headerCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeader($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeader());
    }

    /**
     * @test
     *
     * @return void
     */
    public function headerPositionCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeaderPosition($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderPosition());
    }

    /**
     * @test
     *
     * @return void
     */
    public function bodytextCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setBodytext($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getBodytext());
    }

    /**
     * @test
     *
     * @return void
     */
    public function colPosCanBeSet(): void
    {
        $fieldValue = 1;
        $this->ttContentDomainModelInstance->setColPos($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getColPos());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageCanBeSet(): void
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setImage($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImage());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageWidthCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagewidth($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagewidth());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageOrientCanBeSet(): void
    {
        $fieldValue = 3;
        $this->ttContentDomainModelInstance->setImageorient($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageorient());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageCaptionCanBeSet(): void
    {
        $fieldValue = 'Test123';
        $this->ttContentDomainModelInstance->setImagecaption($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecaption());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageColsCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagecols($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecols());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageBorderCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImageborder($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageborder());
    }

    /**
     * @test
     *
     * @return void
     */
    public function mediaCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setMedia($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getMedia());
    }

    /**
     * @test
     *
     * @return void
     */
    public function layoutCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setLayout($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getLayout());
    }

    /**
     * @test
     *
     * @return void
     */
    public function colsCanBeSet(): void
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setCols($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCols());
    }

    /**
     * @test
     *
     * @return void
     */
    public function subheaderCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setSubheader($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getSubheader());
    }

    /**
     * @test
     *
     * @return void
     */
    public function headerLinkCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLink($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLink());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageLinkCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageLink($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageLink());
    }

    /**
     * @test
     *
     * @return void
     */
    public function imageZoomCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageZoom($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageZoom());
    }

    /**
     * @test
     *
     * @return void
     */
    public function altTextCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setAltText($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getAltText());
    }

    /**
     * @test
     *
     * @return void
     */
    public function titleTextCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setTitleText($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTitleText());
    }

    /**
     * @test
     *
     * @return void
     */
    public function headerLayoutCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLayout($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLayout());
    }

    /**
     * @test
     *
     * @return void
     */
    public function listTypeCanBeSet(): void
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setListType($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getListType());
    }
}
