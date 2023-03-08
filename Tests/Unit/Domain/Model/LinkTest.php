<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

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
     */
    protected function setUp(): void
    {
        $this->linkDomainModelInstance = new Link();
    }

    /**
     * Test if title can be set
     *
     * @test
     */
    public function titleCanBeSet(): void
    {
        $title = 'File title';
        $this->linkDomainModelInstance->setTitle($title);
        self::assertEquals($title, $this->linkDomainModelInstance->getTitle());
    }

    /**
     * Test if crdate can be set
     *
     * @test
     */
    public function crdateCanBeSet(): void
    {
        $time = new \DateTime();
        $this->linkDomainModelInstance->setCrdate($time);
        self::assertEquals($time, $this->linkDomainModelInstance->getCrdate());
    }

    /**
     * Test if tstamp can be set
     *
     * @test
     */
    public function tstampCanBeSet(): void
    {
        $time = new \DateTime();
        $this->linkDomainModelInstance->setTstamp($time);
        self::assertEquals($time, $this->linkDomainModelInstance->getTstamp());
    }

    /**
     * Test if description can be set
     *
     * @test
     */
    public function descriptionCanBeSet(): void
    {
        $description = 'This is a description';
        $this->linkDomainModelInstance->setDescription($description);
        self::assertEquals($description, $this->linkDomainModelInstance->getDescription());
    }

    /**
     * Test if uri can be set
     *
     * @test
     */
    public function uriCanBeSet(): void
    {
        $uri = 'http://typo3.org';
        $this->linkDomainModelInstance->setUri($uri);
        self::assertEquals($uri, $this->linkDomainModelInstance->getUri());
    }
}
