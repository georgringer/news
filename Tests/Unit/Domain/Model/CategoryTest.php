<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

use GeorgRinger\News\Domain\Model\Category;
use GeorgRinger\News\Domain\Model\FileReference;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for \GeorgRinger\News\Domain\Model\Category
 *
 */
class CategoryTest extends BaseTestCase
{

    /**
     * @var Category
     */
    protected $instance;

    /**
     * Setup
     *
     */
    protected function setUp(): void
    {
        $this->instance = new Category();
    }

    /**
     * Test if sorting can be set
     *
     * @test
     *
     * @return void
     */
    public function sortingCanBeSet(): void
    {
        $value = '123';
        $this->instance->setSorting($value);
        $this->assertEquals($value, $this->instance->getSorting());
    }

    /**
     * Test if crdate can be set
     *
     * @test
     *
     * @return void
     */
    public function crdateCanBeSet(): void
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setCrdate($value);
        $this->assertEquals($value, $this->instance->getCrdate());
    }

    /**
     * Test if tstamp can be set
     *
     * @test
     *
     * @return void
     */
    public function tstampCanBeSet(): void
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setTstamp($value);
        $this->assertEquals($value, $this->instance->getTstamp());
    }

    /**
     * Test if starttime can be set
     *
     * @test
     *
     * @return void
     */
    public function starttimeCanBeSet(): void
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setStarttime($value);
        $this->assertEquals($value, $this->instance->getStarttime());
    }

    /**
     * Test if starttime can be set
     *
     * @test
     *
     * @return void
     */
    public function endtimeCanBeSet(): void
    {
        $value = new \DateTime('2014-03-30');
        $this->instance->setEndtime($value);
        $this->assertEquals($value, $this->instance->getEndtime());
    }

    /**
     * Test if hidden can be set
     *
     * @test
     *
     * @return void
     */
    public function hiddenCanBeSet(): void
    {
        $value = true;
        $this->instance->setHidden($value);
        $this->assertEquals($value, $this->instance->getHidden());
    }

    /**
     * Test if sysLanguageUid can be set
     *
     * @test
     *
     * @return void
     */
    public function sysLanguageUidCanBeSet(): void
    {
        $value = 3;
        $this->instance->setSysLanguageUid($value);
        $this->assertEquals($value, $this->instance->getSysLanguageUid());
    }

    /**
     * Test if l10nParent can be set
     *
     * @test
     *
     * @return void
     */
    public function l10nParentCanBeSet(): void
    {
        $value = 5;
        $this->instance->setL10nParent($value);
        $this->assertEquals($value, $this->instance->getL10nParent());
    }

    /**
     * Test if title can be set
     *
     * @test
     *
     * @return void
     */
    public function titleCanBeSet(): void
    {
        $value = 'title';
        $this->instance->setTitle($value);
        $this->assertEquals($value, $this->instance->getTitle());
    }

    /**
     * Test if description can be set
     *
     * @test
     *
     * @return void
     */
    public function descriptionCanBeSet(): void
    {
        $value = 'lorem';
        $this->instance->setDescription($value);
        $this->assertEquals($value, $this->instance->getDescription());
    }

    /**
     * Test if description can be set
     *
     * @test
     *
     * @return void
     */
    public function parentCategoryCanBeSet(): void
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
     *
     * @return void
     */
    public function imagesCanBeSet(): void
    {
        $value = new ObjectStorage();
        $this->instance->setImages($value);
        $this->assertEquals($value, $this->instance->getImages());
    }

    /**
     * Test if first image can be get
     *
     * @test
     *
     * @return void
     */
    public function firstImageCanBeGet(): void
    {
        $storage = new ObjectStorage();
        $item1 = new FileReference();
        $item1->_setProperty('fo', 'bar');
        $storage->attach($item1);
        $item2 = new FileReference();
        $item2->_setProperty('lorem', 'ipsum');
        $storage->attach($item2);

        $this->instance->setImages($storage);
        $this->assertEquals($item1, $this->instance->getFirstImage());
    }

    /**
     * Test if shortcut can be set
     *
     * @test
     *
     * @return void
     */
    public function shortcutCanBeSet(): void
    {
        $value = 789;
        $this->instance->setShortcut($value);
        $this->assertEquals($value, $this->instance->getShortcut());
    }

    /**
     * Test if singlePid can be set
     *
     * @test
     *
     * @return void
     */
    public function singlePidCanBeSet(): void
    {
        $value = 456;
        $this->instance->setSinglePid($value);
        $this->assertEquals($value, $this->instance->getSinglePid());
    }

    /**
     * Test if importId can be set
     *
     * @test
     *
     * @return void
     */
    public function importIdCanBeSet(): void
    {
        $value = 189;
        $this->instance->setImportId($value);
        $this->assertEquals($value, $this->instance->getImportId());
    }

    /**
     * Test if importSource can be set
     *
     * @test
     *
     * @return void
     */
    public function importSourceCanBeSet(): void
    {
        $value = 'something';
        $this->instance->setImportSource($value);
        $this->assertEquals($value, $this->instance->getImportSource());
    }
}
