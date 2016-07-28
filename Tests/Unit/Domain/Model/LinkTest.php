<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

use GeorgRinger\News\Domain\Model\Link;

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

/**
 * Tests for domains model Link
 *
 */
class LinkTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var Link
     */
    protected $linkDomainModelInstance;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->linkDomainModelInstance = new Link();
    }

    /**
     * Test if title can be set
     *
     * @test
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
     */
    public function uriCanBeSet()
    {
        $uri = 'http://typo3.org';
        $this->linkDomainModelInstance->setUri($uri);
        $this->assertEquals($uri, $this->linkDomainModelInstance->getUri());
    }
}
