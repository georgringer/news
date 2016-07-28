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
use GeorgRinger\News\Domain\Model\Category;

/**
 * Tests for \GeorgRinger\News\Domain\Model\Category
 *
 */
class CategoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var Category
     */
    protected $instance;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->instance = new Category();
    }

    /**
     * Test if sorting can be set
     *
     * @test
     * @return void
     */
    public function sortingCanBeSet()
    {
        $value = '123';
        $this->instance->setSorting($value);
        $this->assertEquals($value, $this->instance->getSorting());
    }

    /**
     * Test if crdate can be set
     *
     * @test
     * @return void
     */
    public function crdateCanBeSet()
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setCrdate($value);
        $this->assertEquals($value, $this->instance->getCrdate());
    }

    /**
     * Test if tstamp can be set
     *
     * @test
     * @return void
     */
    public function tstampCanBeSet()
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setTstamp($value);
        $this->assertEquals($value, $this->instance->getTstamp());
    }

    /**
     * Test if starttime can be set
     *
     * @test
     * @return void
     */
    public function starttimeCanBeSet()
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setStarttime($value);
        $this->assertEquals($value, $this->instance->getStarttime());
    }

    /**
     * Test if starttime can be set
     *
     * @test
     * @return void
     */
    public function endtimeCanBeSet()
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setEndtime($value);
        $this->assertEquals($value, $this->instance->getEndtime());
    }

    /**
     * Test if hidden can be set
     *
     * @test
     * @return void
     */
    public function hiddenCanBeSet()
    {
        $value = true;
        $this->instance->setHidden($value);
        $this->assertEquals($value, $this->instance->getHidden());
    }

    /**
     * Test if sysLanguageUid can be set
     *
     * @test
     * @return void
     */
    public function sysLanguageUidCanBeSet()
    {
        $value = 3;
        $this->instance->setSysLanguageUid($value);
        $this->assertEquals($value, $this->instance->getSysLanguageUid());
    }

    /**
     * Test if l10nParent can be set
     *
     * @test
     * @return void
     */
    public function l10nParentCanBeSet()
    {
        $value = 5;
        $this->instance->setL10nParent($value);
        $this->assertEquals($value, $this->instance->getL10nParent());
    }

    /**
     * Test if title can be set
     *
     * @test
     * @return void
     */
    public function titleCanBeSet()
    {
        $value = 'title';
        $this->instance->setTitle($value);
        $this->assertEquals($value, $this->instance->getTitle());
    }

    /**
     * Test if description can be set
     *
     * @test
     * @return void
     */
    public function descriptionCanBeSet()
    {
        $value = 'lorem';
        $this->instance->setDescription($value);
        $this->assertEquals($value, $this->instance->getDescription());
    }

    /**
     * Test if description can be set
     *
     * @test
     * @return void
     */
    public function parentCategoryCanBeSet()
    {
        $value = new Category();
        $value->setTitle('fo');
        $this->instance->setParentcategory($value);
        $this->assertEquals($value, $this->instance->getParentcategory());
    }

    /**
     * Test if images can be set
     *
     * @test
     * @return void
     */
    public function imagesCanBeSet()
    {
        $value = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->instance->setImages($value);
        $this->assertEquals($value, $this->instance->getImages());
    }

    /**
     * Test if first image can be get
     *
     * @test
     * @return void
     */
    public function firstImageCanBeGet()
    {
        $storage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $item1 = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $item1->_setProperty('fo', 'bar');
        $storage->attach($item1);
        $item2 = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $item2->_setProperty('lorem', 'ipsum');
        $storage->attach($item2);

        $this->instance->setImages($storage);
        $this->assertEquals($item1, $this->instance->getFirstImage());
    }

    /**
     * Test if shortcut can be set
     *
     * @test
     * @return void
     */
    public function shortcutCanBeSet()
    {
        $value = 789;
        $this->instance->setShortcut($value);
        $this->assertEquals($value, $this->instance->getShortcut());
    }

    /**
     * Test if singlePid can be set
     *
     * @test
     * @return void
     */
    public function singlePidCanBeSet()
    {
        $value = 456;
        $this->instance->setSinglePid($value);
        $this->assertEquals($value, $this->instance->getSinglePid());
    }

    /**
     * Test if importId can be set
     *
     * @test
     * @return void
     */
    public function importIdCanBeSet()
    {
        $value = 189;
        $this->instance->setImportId($value);
        $this->assertEquals($value, $this->instance->getImportId());
    }
    /**
     * Test if importSource can be set
     *
     * @test
     * @return void
     */
    public function importSourceCanBeSet()
    {
        $value = 'something';
        $this->instance->setImportSource($value);
        $this->assertEquals($value, $this->instance->getImportSource());
    }
}
