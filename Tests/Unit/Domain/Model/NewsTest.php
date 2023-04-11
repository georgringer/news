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
use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\Tag;
use TYPO3\CMS\Core\Resource\FileReference as FileReferenceCore;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for domains model News
 */
class NewsTest extends BaseTestCase
{
    /**
     * @var News
     */
    protected $newsDomainModelInstance;

    /**
     * Set up framework
     */
    protected function setUp(): void
    {
        $this->newsDomainModelInstance = new News();
    }

    /**
     * Test if title can be set
     *
     * @test
     */
    public function titleCanBeSet(): void
    {
        $title = 'News title';
        $this->newsDomainModelInstance->setTitle($title);
        self::assertEquals($title, $this->newsDomainModelInstance->getTitle());
    }

    /**
     * Test if teaser can be set
     *
     * @test
     */
    public function teaserCanBeSet(): void
    {
        $teaser = 'News teaser';
        $this->newsDomainModelInstance->setTeaser($teaser);
        self::assertEquals($teaser, $this->newsDomainModelInstance->getTeaser());
    }

    /**
     * Test if bodytext can be set
     *
     * @test
     */
    public function bodytextCanBeSet(): void
    {
        $bodytext = 'News bodytext';
        $this->newsDomainModelInstance->setBodytext($bodytext);
        self::assertEquals($bodytext, $this->newsDomainModelInstance->getBodytext());
    }

    /**
     * Test if datetime can be set
     *
     * @test
     */
    public function datetimeCanBeSet(): void
    {
        $datetime = new \DateTime();
        $this->newsDomainModelInstance->setDatetime($datetime);
        self::assertEquals($datetime, $this->newsDomainModelInstance->getDatetime());
    }

    /**
     * Test if archive can be set
     *
     * @test
     */
    public function archiveCanBeSet(): void
    {
        $archive = new \DateTime();
        $this->newsDomainModelInstance->setArchive($archive);
        self::assertEquals($archive, $this->newsDomainModelInstance->getArchive());
    }

    /**
     * Test if author can be set
     *
     * @test
     */
    public function authorCanBeSet(): void
    {
        $author = 'News author';
        $this->newsDomainModelInstance->setAuthor($author);
        self::assertEquals($author, $this->newsDomainModelInstance->getAuthor());
    }

    /**
     * Test if emailadr can be set
     *
     * @test
     */
    public function authorEmailCanBeSet(): void
    {
        $authorEmail = 'author@news.org';
        $this->newsDomainModelInstance->setAuthorEmail($authorEmail);
        self::assertEquals($authorEmail, $this->newsDomainModelInstance->getAuthorEmail());
    }

    /**
     * Test if type can be set
     *
     * @test
     */
    public function typeCanBeSet(): void
    {
        $type = 123;
        $this->newsDomainModelInstance->setType($type);
        self::assertEquals($type, $this->newsDomainModelInstance->getType());
    }

    /**
     * Test if keyword can be set
     *
     * @test
     */
    public function keywordsCanBeSet(): void
    {
        $keywords = 'news1 keyword, news keyword';
        $this->newsDomainModelInstance->setKeywords($keywords);
        self::assertEquals($keywords, $this->newsDomainModelInstance->getKeywords());
    }

    /**
     * Test if internalurl can be set
     *
     * @test
     */
    public function internalurlCanBeSet(): void
    {
        $internalurl = 'http://foo.org/';
        $this->newsDomainModelInstance->setInternalurl($internalurl);
        self::assertEquals($internalurl, $this->newsDomainModelInstance->getInternalurl());
    }

    /**
     * Test if externalurl can be set
     *
     * @test
     */
    public function externalurlCanBeSet(): void
    {
        $externalurl = 'http://bar.org/';
        $this->newsDomainModelInstance->setExternalurl($externalurl);
        self::assertEquals($externalurl, $this->newsDomainModelInstance->getExternalurl());
    }

    /**
     * Test if topnews can be set
     *
     * @test
     */
    public function isttopnewsCanBeSet(): void
    {
        $istopnews = true;
        $this->newsDomainModelInstance->setIstopnews($istopnews);
        self::assertEquals($istopnews, $this->newsDomainModelInstance->getIstopnews());
    }

    /**
     * Test if editlock can be set
     *
     * @test
     */
    public function editlockCanBeSet(): void
    {
        $editlock = 2;
        $this->newsDomainModelInstance->setEditlock($editlock);
        self::assertEquals($editlock, $this->newsDomainModelInstance->getEditlock());
    }

    /**
     * Test if importid can be set
     *
     * @test
     */
    public function importIdCanBeSet(): void
    {
        $importId = 2;
        $this->newsDomainModelInstance->setImportId($importId);
        self::assertEquals($importId, $this->newsDomainModelInstance->getImportId());
    }

    /**
     * Test if importSource can be set
     *
     * @test
     */
    public function importSourceCanBeSet(): void
    {
        $importSource = 'test';
        $this->newsDomainModelInstance->setImportSource($importSource);
        self::assertEquals($importSource, $this->newsDomainModelInstance->getImportSource());
    }

    /**
     * Test if sorting can be set
     *
     * @test
     */
    public function sortingCanBeSet(): void
    {
        $sorting = 2;
        $this->newsDomainModelInstance->setSorting($sorting);
        self::assertEquals($sorting, $this->newsDomainModelInstance->getSorting());
    }

    /**
     * Test if tag can be set
     *
     * @test
     */
    public function tagsCanBeSet(): void
    {
        $tags = new ObjectStorage();

        $tag = new Tag();
        $tag->setTitle('Tag');
        $tags->attach($tag);
        $this->newsDomainModelInstance->setTags($tags);
        self::assertEquals($tags, $this->newsDomainModelInstance->getTags());
    }

    /**
     * Test if content elements can be set
     *
     * @test
     */
    public function contentElementsCanBeSet(): void
    {
        $ce = new ObjectStorage();

        $item = new \SplObjectStorage();
        $ce->attach($item);

        $this->newsDomainModelInstance->setContentElements($ce);
        self::assertEquals($ce, $this->newsDomainModelInstance->getContentElements());
    }

    /**
     * Test if category can be set
     *
     * @test
     */
    public function categoryCanBeSet(): void
    {
        $category = new Category();
        $category->setTitle('fo');
        $categories = new ObjectStorage();
        $categories->attach($category);
        $this->newsDomainModelInstance->setCategories($categories);
        self::assertEquals($categories, $this->newsDomainModelInstance->getCategories());
    }

    /**
     * Test if related links can be set
     *
     * @test
     */
    public function relatedLinksCanBeSet(): void
    {
        $link = new Link();
        $link->setTitle('fo');

        $related = new ObjectStorage();
        $related->attach($link);
        $this->newsDomainModelInstance->setRelatedLinks($related);
        self::assertEquals($related, $this->newsDomainModelInstance->getRelatedLinks());
    }

    /**
     * @test
     */
    public function falMediaCanBeAdded(): void
    {
        $mediaItem = new FileReference();
        $mediaItem->setTitle('Fo');

        $news = new News();
        $news->addFalMedia($mediaItem);

        self::assertEquals($news->getFalMedia()->current(), $mediaItem);
        self::assertEquals($news->getMedia()->current(), $mediaItem);
    }

    /**
     * @test
     */
    public function falMediaPreviewsAreReturned(): void
    {
        $news = new News();

        $mockedElement1 = $this->getAccessibleMock(FileReferenceCore::class, ['getProperty'], [], '', false);
        $mockedElement1->_set('uid', 1);
        $mockedElement1->_set('showinpreview', 1);
        $mockedElement1->expects(self::any())->method('getProperty')->willReturn(1);

        $mediaItem1 = new FileReference();
        $mediaItem1->_setProperty('originalResource', $mockedElement1);
        $mediaItem1->_setProperty('uid', 1);
        $news->addFalMedia($mediaItem1);

        $mockedElement2 = $this->getAccessibleMock(FileReferenceCore::class, ['getProperty'], [], '', false);
        $mockedElement2->_set('uid', 2);
        $mockedElement2->_set('showinpreview', 0);
        $mockedElement2->expects(self::any())->method('getProperty')->willReturn(0);

        $mediaItem2 = new FileReference();
        $mediaItem2->_setProperty('originalResource', $mockedElement2);
        $mediaItem2->_setProperty('uid', 2);
        $news->addFalMedia($mediaItem2);

        $mockedElement3 = $this->getAccessibleMock(FileReferenceCore::class, ['getProperty'], [], '', false);
        $mockedElement3->_set('uid', 3);
        $mockedElement3->_set('showinpreview', 1);
        $mockedElement3->expects(self::any())->method('getProperty')->willReturn(1);

        $mediaItem3 = new FileReference();
        $mediaItem3->_setProperty('uid', 3);
        $mediaItem3->_setProperty('originalResource', $mockedElement3);
        $news->addFalMedia($mediaItem3);

        $mockedElement4 = $this->getAccessibleMock(FileReferenceCore::class, ['getProperty'], [], '', false);
        $mockedElement4->_set('uid', 4);
        $mockedElement4->_set('showinpreview', 2);
        $mockedElement4->expects(self::any())->method('getProperty')->willReturn(2);

        $mediaItem4 = new FileReference();
        $mediaItem4->_setProperty('uid', 4);
        $mediaItem4->_setProperty('originalResource', $mockedElement4);
        $news->addFalMedia($mediaItem4);

        self::assertEquals(3, count($news->getMediaPreviews()));
        self::assertEquals(3, count($news->getMediaNonPreviews()));

        self::assertEquals(4, count($news->getFalMedia()));
        self::assertEquals(4, count($news->getMedia()));
    }
}
