<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Link;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for domains model Link
 */
class LinkTest extends BaseTestCase
{
    /**
     * @var Link
     */
    protected $linkDomainModelInstance;

    /**
     * Setup
     *
     */
    protected function setUp(): void
    {
        $this->linkDomainModelInstance = new Link();
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
        $title = 'File title';
        $this->linkDomainModelInstance->setTitle($title);
        $this->assertEquals($title, $this->linkDomainModelInstance->getTitle());
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
        $time = new \DateTime();
        $this->linkDomainModelInstance->setCrdate($time);
        $this->assertEquals($time, $this->linkDomainModelInstance->getCrdate());
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
        $time = new \DateTime();
        $this->linkDomainModelInstance->setTstamp($time);
        $this->assertEquals($time, $this->linkDomainModelInstance->getTstamp());
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
        $description = 'This is a description';
        $this->linkDomainModelInstance->setDescription($description);
        $this->assertEquals($description, $this->linkDomainModelInstance->getDescription());
    }

    /**
     * Test if uri can be set
     *
     * @test
     *
     * @return void
     */
    public function uriCanBeSet(): void
    {
        $uri = 'http://typo3.org';
        $this->linkDomainModelInstance->setUri($uri);
        $this->assertEquals($uri, $this->linkDomainModelInstance->getUri());
    }
}
