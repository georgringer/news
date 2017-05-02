<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Tag;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for domains model Tag
 *
 */
class TagTest extends UnitTestCase
{

    /**
     * @var Tag
     */
    protected $tagDomainModelInstance;

    /**
     * Setup
     *
     */
    protected function setUp()
    {
        $this->tagDomainModelInstance = new Tag();
    }

    /**
     * Test if title can be set
     *
     * @test
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
     */
    public function tstampCanBeSet()
    {
        $time = new \DateTime('now');
        $this->tagDomainModelInstance->setTstamp($time);
        $this->assertEquals($time, $this->tagDomainModelInstance->getTstamp());
    }
}
