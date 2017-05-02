<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Link;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for domains model Link
 */
class LinkTest extends UnitTestCase
{

    /**
     * @var Link
     */
    protected $linkDomainModelInstance;

    /**
     * Setup
     *
     */
    protected function setUp()
    {
        $this->linkDomainModelInstance = new Link();
    }

    /**
     * Test if title can be set
     *
     * @test
     */
    public function titleCanBeSet()
    {
        $title = 'File title';
        $this->linkDomainModelInstance->setTitle($title);
        $this->assertEquals($title, $this->linkDomainModelInstance->getTitle());
    }

    /**
     * Test if crdate can be set
     *
     * @test
     */
    public function crdateCanBeSet()
    {
        $time = time();
        $this->linkDomainModelInstance->setCrdate($time);
        $this->assertEquals($time, $this->linkDomainModelInstance->getCrdate());
    }

    /**
     * Test if tstamp can be set
     *
     * @test
     */
    public function tstampCanBeSet()
    {
        $time = time();
        $this->linkDomainModelInstance->setTstamp($time);
        $this->assertEquals($time, $this->linkDomainModelInstance->getTstamp());
    }

    /**
     * Test if description can be set
     *
     * @test
     */
    public function descriptionCanBeSet()
    {
        $description = 'This is a description';
        $this->linkDomainModelInstance->setDescription($description);
        $this->assertEquals($description, $this->linkDomainModelInstance->getDescription());
    }

    /**
     * Test if uri can be set
     *
     * @test
     */
    public function uriCanBeSet()
    {
        $uri = 'http://typo3.org';
        $this->linkDomainModelInstance->setUri($uri);
        $this->assertEquals($uri, $this->linkDomainModelInstance->getUri());
    }
}
