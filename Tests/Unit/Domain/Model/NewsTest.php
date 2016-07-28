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
use GeorgRinger\News\Domain\Model\File;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\Tag;

/**
 * Tests for domains model News
 *
 */
class NewsTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var News
     */
    protected $newsDomainModelInstance;

    /**
     * Set up framework
     *
     * @return void
     */
    protected function setUp()
    {
        $this->newsDomainModelInstance = new News();
    }

    /**
     * Test if title can be set
     *
     * @test
     * @return void
     */
    public function titleCanBeSet()
    {
        $title = 'News title';
        $this->newsDomainModelInstance->setTitle($title);
        $this->assertEquals($title, $this->newsDomainModelInstance->getTitle());
    }

    /**
     * Test if teaser can be set
     *
     * @test
     * @return void
     */
    public function teaserCanBeSet()
    {
        $teaser = 'News teaser';
        $this->newsDomainModelInstance->setTeaser($teaser);
        $this->assertEquals($teaser, $this->newsDomainModelInstance->getTeaser());
    }

    /**
     * Test if bodytext can be set
     *
     * @test
     * @return void
     */
    public function bodytextCanBeSet()
    {
        $bodytext = 'News bodytext';
        $this->newsDomainModelInstance->setBodytext($bodytext);
        $this->assertEquals($bodytext, $this->newsDomainModelInstance->getBodytext());
    }

    /**
     * Test if datetime can be set
     *
     * @test
     * @return void
     */
    public function datetimeCanBeSet()
    {
        $datetime = new \DateTime();
        $this->newsDomainModelInstance->setDatetime($datetime);
        $this->assertEquals($datetime, $this->newsDomainModelInstance->getDatetime());
    }

    /**
     * Test if archive can be set
     *
     * @test
     * @return void
     */
    public function archiveCanBeSet()
    {
        $archive = new \DateTime();
        $this->newsDomainModelInstance->setArchive($archive);
        $this->assertEquals($archive, $this->newsDomainModelInstance->getArchive());
    }

    /**
     * Test if author can be set
     *
     * @test
     * @return void
     */
    public function authorCanBeSet()
    {
        $author = 'News author';
        $this->newsDomainModelInstance->setAuthor($author);
        $this->assertEquals($author, $this->newsDomainModelInstance->getAuthor());
    }

    /**
     * Test if emailadr can be set
     *
     * @test
     * @return void
     */
    public function authorEmailCanBeSet()
    {
        $authorEmail = 'author@news.org';
        $this->newsDomainModelInstance->setAuthorEmail($authorEmail);
        $this->assertEquals($authorEmail, $this->newsDomainModelInstance->getAuthorEmail());
    }

    /**
     * Test if type can be set
     *
     * @test
     * @return void
     */
    public function typeCanBeSet()
    {
        $type = 123;
        $this->newsDomainModelInstance->setType($type);
        $this->assertEquals($type, $this->newsDomainModelInstance->getType());
    }

    /**
     * Test if keyword can be set
     *
     * @test
     * @return void
     */
    public function keywordsCanBeSet()
    {
        $keywords = 'news1 keyword, news keyword';
        $this->newsDomainModelInstance->setKeywords($keywords);
        $this->assertEquals($keywords, $this->newsDomainModelInstance->getKeywords());
    }

    /**
     * Test if internalurl can be set
     *
     * @test
     * @return void
     */
    public function internalurlCanBeSet()
    {
        $internalurl = 'http://foo.org/';
        $this->newsDomainModelInstance->setInternalurl($internalurl);
        $this->assertEquals($internalurl, $this->newsDomainModelInstance->getInternalurl());
    }

    /**
     * Test if externalurl can be set
     *
     * @test
     * @return void
     */
    public function externalurlCanBeSet()
    {
        $externalurl = 'http://bar.org/';
        $this->newsDomainModelInstance->setExternalurl($externalurl);
        $this->assertEquals($externalurl, $this->newsDomainModelInstance->getExternalurl());
    }

    /**
     * Test if topnews can be set
     *
     * @test
     * @return void
     */
    public function isttopnewsCanBeSet()
    {
        $istopnews = true;
        $this->newsDomainModelInstance->setIstopnews($istopnews);
        $this->assertEquals($istopnews, $this->newsDomainModelInstance->getIstopnews());
    }

    /**
     * Test if editlock can be set
     *
     * @test
     * @return void
     */
    public function editlockCanBeSet()
    {
        $editlock = 2;
        $this->newsDomainModelInstance->setEditlock($editlock);
        $this->assertEquals($editlock, $this->newsDomainModelInstance->getEditlock());
    }

    /**
     * Test if importid can be set
     *
     * @test
     * @return void
     */
    public function importIdCanBeSet()
    {
        $importId = 2;
        $this->newsDomainModelInstance->setImportId($importId);
        $this->assertEquals($importId, $this->newsDomainModelInstance->getImportId());
    }

    /**
     * Test if importSource can be set
     *
     * @test
     * @return void
     */
    public function importSourceCanBeSet()
    {
        $importSource = 'test';
        $this->newsDomainModelInstance->setImportSource($importSource);
        $this->assertEquals($importSource, $this->newsDomainModelInstance->getImportSource());
    }

    /**
     * Test if sorting can be set
     *
     * @test
     * @return void
     */
    public function sortingCanBeSet()
    {
        $sorting = 2;
        $this->newsDomainModelInstance->setSorting($sorting);
        $this->assertEquals($sorting, $this->newsDomainModelInstance->getSorting());
    }

    /**
     * Test if tag can be set
     *
     * @test
     * @return void
     */
    public function tagsCanBeSet()
    {
        $tags = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

        $tag = new Tag();
        $tag->setTitle('Tag');
        $tags->attach($tags);
        $this->newsDomainModelInstance->setTags($tags);
        $this->assertEquals($tags, $this->newsDomainModelInstance->getTags());
    }

    /**
     * Test if content elements can be set
     *
     * @test
     * @return void
     */
    public function contentElementsCanBeSet()
    {
        $ce = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

        $item = new \SplObjectStorage();
        $ce->attach($item);

        $this->newsDomainModelInstance->setContentElements($ce);
        $this->assertEquals($ce, $this->newsDomainModelInstance->getContentElements());
    }

    /**
     * Test if category can be set
     *
     * @test
     * @return void
     */
    public function categoryCanBeSet()
    {
        $category = new Category();
        $category->setTitle('fo');
        $categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $categories->attach($category);
        $this->newsDomainModelInstance->setCategories($categories);
        $this->assertEquals($categories, $this->newsDomainModelInstance->getCategories());
    }

    /**
     * Test if related links can be set
     *
     * @test
     * @return void
     */
    public function relatedLinksCanBeSet()
    {
        $link = new Link();
        $link->setTitle('fo');

        $related = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $related->attach($link);
        $this->newsDomainModelInstance->setRelatedLinks($related);
        $this->assertEquals($related, $this->newsDomainModelInstance->getRelatedLinks());
    }

    /**
     * @test
     */
    public function falMediaCanBeAdded()
    {
        $mediaItem = new FileReference();
        $mediaItem->setTitle('Fo');

        $news = new News();
        $news->addFalMedia($mediaItem);

        $this->assertEquals($news->getFalMedia()->current(), $mediaItem);
    }

    /**
     * @test
     */
    public function falMediaPreviewsAreReturned()
    {
        $news = new News();

        $mockedElement1 = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Model\\FileReference', ['getProperty']);
        $mockedElement1->_set('uid', 1);
        $mockedElement1->_set('showinpreview', true);
        $mockedElement1->expects($this->any())->method('getProperty')->will($this->returnValue(true));

        $mediaItem1 = new FileReference();
        $mediaItem1->_setProperty('originalResource', $mockedElement1);
        $news->addFalMedia($mediaItem1);

        $mockedElement2 = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Model\\FileReference', ['getProperty']);
        $mockedElement2->_set('uid', 2);
        $mockedElement2->_set('showinpreview', true);
        $mockedElement2->expects($this->any())->method('getProperty')->will($this->returnValue(false));

        $mediaItem2 = new FileReference();
        $mediaItem2->_setProperty('originalResource', $mockedElement2);
        $news->addFalMedia($mediaItem2);

        $mockedElement3 = $this->getAccessibleMock('GeorgRinger\\News\\Domain\\Model\\FileReference', ['getProperty']);
        $mockedElement3->_set('uid', 3);
        $mockedElement3->_set('showinpreview', true);
        $mockedElement3->expects($this->any())->method('getProperty')->will($this->returnValue(true));

        $mediaItem3 = new FileReference();
        $mediaItem3->_setProperty('originalResource', $mockedElement3);
        $news->addFalMedia($mediaItem3);

        $this->assertEquals(2, count($news->getFalMediaPreviews()));
        $this->assertEquals(1, count($news->getFalMediaNonPreviews()));
        $this->assertEquals(3, count($news->getFalMedia()));
    }
}
