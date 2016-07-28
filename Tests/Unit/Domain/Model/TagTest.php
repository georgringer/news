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
use GeorgRinger\News\Domain\Model\Tag;

/**
 * Tests for domains model Tag
 *
 */
class TagTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var Tag
     */
    protected $tagDomainModelInstance;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->tagDomainModelInstance = new Tag();
    }

    /**
     * Test if title can be set
     *
     * @test
     * @return void
     */
    public function titleCanBeSet()
    {
        $title = 'Tag title';
        $this->tagDomainModelInstance->setTitle($title);
        $this->assertEquals($title, $this->tagDomainModelInstance->getTitle());
    }

    /**
     * Test if crdate can be set
     *
     * @test
     * @return void
     */
    public function crdateCanBeSet()
    {
        $time = new \DateTime('now');
        $this->tagDomainModelInstance->setCrdate($time);
        $this->assertEquals($time, $this->tagDomainModelInstance->getCrdate());
    }

    /**
     * Test if tstamp can be set
     *
     * @test
     * @return void
     */
    public function tstampCanBeSet()
    {
        $time = new \DateTime('now');
        $this->tagDomainModelInstance->setTstamp($time);
        $this->assertEquals($time, $this->tagDomainModelInstance->getTstamp());
    }
}
