<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

use GeorgRinger\News\Domain\Model\Category;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\Tag;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for domains model News
 *
 */
class NewsTest extends BaseTestCase
{
    /**
     * @var News
     */
    protected $newsDomainModelInstance;

    /**
     * Set up framework
     *
     */
    protected function setUp(): void
    {
        $this->newsDomainModelInstance = new News();
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
        $title = 'News title';
        $this->newsDomainModelInstance->setTitle($title);
        $this->assertEquals($title, $this->newsDomainModelInstance->getTitle());
    }

    /**
     * Test if teaser can be set
     *
     * @test
     *
     * @return void
     */
    public function teaserCanBeSet(): void
    {
        $teaser = 'News teaser';
        $this->newsDomainModelInstance->setTeaser($teaser);
        $this->assertEquals($teaser, $this->newsDomainModelInstance->getTeaser());
    }

    /**
     * Test if bodytext can be set
     *
     * @test
     *
     * @return void
     */
    public function bodytextCanBeSet(): void
    {
        $bodytext = 'News bodytext';
        $this->newsDomainModelInstance->setBodytext($bodytext);
        $this->assertEquals($bodytext, $this->newsDomainModelInstance->getBodytext());
    }

    /**
     * Test if datetime can be set
     *
     * @test
     *
     * @return void
     */
    public function datetimeCanBeSet(): void
    {
        $datetime = new \DateTime();
        $this->newsDomainModelInstance->setDatetime($datetime);
        $this->assertEquals($datetime, $this->newsDomainModelInstance->getDatetime());
    }

    /**
     * Test if archive can be set
     *
     * @test
     *
     * @return void
     */
    public function archiveCanBeSet(): void
    {
        $archive = new \DateTime();
        $this->newsDomainModelInstance->setArchive($archive);
        $this->assertEquals($archive, $this->newsDomainModelInstance->getArchive());
    }

    /**
     * Test if author can be set
     *
     * @test
     *
     * @return void
     */
    public function authorCanBeSet(): void
    {
        $author = 'News author';
        $this->newsDomainModelInstance->setAuthor($author);
        $this->assertEquals($author, $this->newsDomainModelInstance->getAuthor());
    }

    /**
     * Test if emailadr can be set
     *
     * @test
     *
     * @return void
     */
    public function authorEmailCanBeSet(): void
    {
        $authorEmail = 'author@news.org';
        $this->newsDomainModelInstance->setAuthorEmail($authorEmail);
        $this->assertEquals($authorEmail, $this->newsDomainModelInstance->getAuthorEmail());
    }

    /**
     * Test if type can be set
     *
     * @test
     *
     * @return void
     */
    public function typeCanBeSet(): void
    {
        $type = 123;
        $this->newsDomainModelInstance->setType($type);
        $this->assertEquals($type, $this->newsDomainModelInstance->getType());
    }

    /**
     * Test if keyword can be set
     *
     * @test
     *
     * @return void
     */
    public function keywordsCanBeSet(): void
    {
        $keywords = 'news1 keyword, news keyword';
        $this->newsDomainModelInstance->setKeywords($keywords);
        $this->assertEquals($keywords, $this->newsDomainModelInstance->getKeywords());
    }

    /**
     * Test if internalurl can be set
     *
     * @test
     *
     * @return void
     */
    public function internalurlCanBeSet(): void
    {
        $internalurl = 'http://foo.org/';
        $this->newsDomainModelInstance->setInternalurl($internalurl);
        $this->assertEquals($internalurl, $this->newsDomainModelInstance->getInternalurl());
    }

    /**
     * Test if externalurl can be set
     *
     * @test
     *
     * @return void
     */
    public function externalurlCanBeSet(): void
    {
        $externalurl = 'http://bar.org/';
        $this->newsDomainModelInstance->setExternalurl($externalurl);
        $this->assertEquals($externalurl, $this->newsDomainModelInstance->getExternalurl());
    }

    /**
     * Test if topnews can be set
     *
     * @test
     *
     * @return void
     */
    public function isttopnewsCanBeSet(): void
    {
        $istopnews = true;
        $this->newsDomainModelInstance->setIstopnews($istopnews);
        $this->assertEquals($istopnews, $this->newsDomainModelInstance->getIstopnews());
    }

    /**
     * Test if editlock can be set
     *
     * @test
     *
     * @return void
     */
    public function editlockCanBeSet(): void
    {
        $editlock = 2;
        $this->newsDomainModelInstance->setEditlock($editlock);
        $this->assertEquals($editlock, $this->newsDomainModelInstance->getEditlock());
    }

    /**
     * Test if importid can be set
     *
     * @test
     *
     * @return void
     */
    public function importIdCanBeSet(): void
    {
        $importId = 2;
        $this->newsDomainModelInstance->setImportId($importId);
        $this->assertEquals($importId, $this->newsDomainModelInstance->getImportId());
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
        $importSource = 'test';
        $this->newsDomainModelInstance->setImportSource($importSource);
        $this->assertEquals($importSource, $this->newsDomainModelInstance->getImportSource());
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
        $sorting = 2;
        $this->newsDomainModelInstance->setSorting($sorting);
        $this->assertEquals($sorting, $this->newsDomainModelInstance->getSorting());
    }

    /**
     * Test if tag can be set
     *
     * @test
     *
     * @return void
     */
    public function tagsCanBeSet(): void
    {
        $tags = new ObjectStorage();

        $tag = new Tag();
        $tag->setTitle('Tag');
        $tags->attach($tag);
        $this->newsDomainModelInstance->setTags($tags);
        $this->assertEquals($tags, $this->newsDomainModelInstance->getTags());
    }

    /**
     * Test if content elements can be set
     *
     * @test
     *
     * @return void
     */
    public function contentElementsCanBeSet(): void
    {
        $ce = new ObjectStorage();

        $item = new \SplObjectStorage();
        $ce->attach($item);

        $this->newsDomainModelInstance->setContentElements($ce);
        $this->assertEquals($ce, $this->newsDomainModelInstance->getContentElements());
    }

    /**
     * Test if category can be set
     *
     * @test
     *
     * @return void
     */
    public function categoryCanBeSet(): void
    {
        $category = new Category();
        $category->setTitle('fo');
        $categories = new ObjectStorage();
        $categories->attach($category);
        $this->newsDomainModelInstance->setCategories($categories);
        $this->assertEquals($categories, $this->newsDomainModelInstance->getCategories());
    }

    /**
     * Test if related links can be set
     *
     * @test
     *
     * @return void
     */
    public function relatedLinksCanBeSet(): void
    {
        $link = new Link();
        $link->setTitle('fo');

        $related = new ObjectStorage();
        $related->attach($link);
        $this->newsDomainModelInstance->setRelatedLinks($related);
        $this->assertEquals($related, $this->newsDomainModelInstance->getRelatedLinks());
    }

    /**
     * @test
     *
     * @return void
     */
    public function falMediaCanBeAdded(): void
    {
        $mediaItem = new FileReference();
        $mediaItem->setTitle('Fo');

        $news = new News();
        $news->addFalMedia($mediaItem);

        $this->assertEquals($news->getFalMedia()->current(), $mediaItem);
        $this->assertEquals($news->getMedia()->current(), $mediaItem);
    }

    /**
     * @test
     *
     * @return void
     */
    public function falMediaPreviewsAreReturned(): void
    {
        $news = new News();

        $mockedElement1 = $this->getAccessibleMock(FileReference::class, ['getProperty']);
        $mockedElement1->_set('uid', 1);
        $mockedElement1->_set('showinpreview', 1);
        $mockedElement1->expects($this->any())->method('getProperty')->will($this->returnValue(1));

        $mediaItem1 = new FileReference();
        $mediaItem1->_setProperty('originalResource', $mockedElement1);
        $mediaItem1->_setProperty('uid', 1);
        $news->addFalMedia($mediaItem1);

        $mockedElement2 = $this->getAccessibleMock(FileReference::class, ['getProperty']);
        $mockedElement2->_set('uid', 2);
        $mockedElement2->_set('showinpreview', 0);
        $mockedElement2->expects($this->any())->method('getProperty')->will($this->returnValue(0));

        $mediaItem2 = new FileReference();
        $mediaItem2->_setProperty('originalResource', $mockedElement2);
        $mediaItem2->_setProperty('uid', 2);
        $news->addFalMedia($mediaItem2);

        $mockedElement3 = $this->getAccessibleMock(FileReference::class, ['getProperty']);
        $mockedElement3->_set('uid', 3);
        $mockedElement3->_set('showinpreview', 1);
        $mockedElement3->expects($this->any())->method('getProperty')->will($this->returnValue(1));

        $mediaItem3 = new FileReference();
        $mediaItem3->_setProperty('uid', 3);
        $mediaItem3->_setProperty('originalResource', $mockedElement3);
        $news->addFalMedia($mediaItem3);

        $mockedElement4 = $this->getAccessibleMock(FileReference::class, ['getProperty']);
        $mockedElement4->_set('uid', 4);
        $mockedElement4->_set('showinpreview', 2);
        $mockedElement4->expects($this->any())->method('getProperty')->will($this->returnValue(2));

        $mediaItem4 = new FileReference();
        $mediaItem4->_setProperty('uid', 4);
        $mediaItem4->_setProperty('originalResource', $mockedElement4);
        $news->addFalMedia($mediaItem4);

        $this->assertEquals(3, count($news->getMediaPreviews()));
        $this->assertEquals(3, count($news->getMediaNonPreviews()));

        $this->assertEquals(4, count($news->getFalMedia()));
        $this->assertEquals(4, count($news->getMedia()));
    }
}
