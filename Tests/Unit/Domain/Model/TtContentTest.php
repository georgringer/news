<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

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
use GeorgRinger\News\Domain\Model\TtContent;

/**
 * Tests for tt_content model
 *
 */
class TtContentTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var TtContent
     */
    protected $ttContentDomainModelInstance;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->ttContentDomainModelInstance = new TtContent();
    }

    /**
     * @test
     */
    public function crdateCanBeSet()
    {
        $fieldValue = new \DateTime();
        $this->ttContentDomainModelInstance->setCrdate($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCrdate());
    }

    /**
     * @test
     */
    public function tstampCanBeSet()
    {
        $fieldValue = new \DateTime();
        $this->ttContentDomainModelInstance->setTstamp($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTstamp());
    }

    /**
     * @test
     */
    public function cTypeCanBeSet()
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setCType($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCType());
    }

    /**
     * @test
     */
    public function headerCanBeSet()
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeader($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeader());
    }

    /**
     * @test
     */
    public function headerPositionCanBeSet()
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setHeaderPosition($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderPosition());
    }

    /**
     * @test
     */
    public function bodytextCanBeSet()
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setBodytext($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getBodytext());
    }

    /**
     * @test
     */
    public function colPosCanBeSet()
    {
        $fieldValue = 1;
        $this->ttContentDomainModelInstance->setColPos($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getColPos());
    }

    /**
     * @test
     */
    public function imageCanBeSet()
    {
        $fieldValue = 'fo123';
        $this->ttContentDomainModelInstance->setImage($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImage());
    }

    /**
     * @test
     */
    public function imageWidthCanBeSet()
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagewidth($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagewidth());
    }

    /**
     * @test
     */
    public function imageOrientCanBeSet()
    {
        $fieldValue = 'Test123';
        $this->ttContentDomainModelInstance->setImageorient($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageorient());
    }

    /**
     * @test
     */
    public function imageCaptionCanBeSet()
    {
        $fieldValue = 'Test123';
        $this->ttContentDomainModelInstance->setImagecaption($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecaption());
    }

    /**
     * @test
     */
    public function imageColsCanBeSet()
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImagecols($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImagecols());
    }

    /**
     * @test
     */
    public function imageBorderCanBeSet()
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setImageborder($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageborder());
    }

    /**
     * @test
     */
    public function mediaCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setMedia($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getMedia());
    }

    /**
     * @test
     */
    public function layoutCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setLayout($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getLayout());
    }

    /**
     * @test
     */
    public function colsCanBeSet()
    {
        $fieldValue = 123;
        $this->ttContentDomainModelInstance->setCols($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getCols());
    }

    /**
     * @test
     */
    public function subheaderCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setSubheader($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getSubheader());
    }

    /**
     * @test
     */
    public function headerLinkCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLink($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLink());
    }

    /**
     * @test
     */
    public function imageLinkCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageLink($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageLink());
    }

    /**
     * @test
     */
    public function imageZoomCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setImageZoom($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getImageZoom());
    }

    /**
     * @test
     */
    public function altTextCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setAltText($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getAltText());
    }

    /**
     * @test
     */
    public function titleTextCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setTitleText($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getTitleText());
    }

    /**
     * @test
     */
    public function headerLayoutCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setHeaderLayout($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getHeaderLayout());
    }

    /**
     * @test
     */
    public function listTypeCanBeSet()
    {
        $fieldValue = 'Test 123';
        $this->ttContentDomainModelInstance->setListType($fieldValue);
        $this->assertEquals($fieldValue, $this->ttContentDomainModelInstance->getListType());
    }
}
