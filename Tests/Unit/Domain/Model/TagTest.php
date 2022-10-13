<?php

namespace GeorgRinger\News\Tests\Unit\Domain\Model;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\Tag;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for domains model Tag
 *
 */
class TagTest extends BaseTestCase
{
    /**
     * @var Tag
     */
    protected $tagDomainModelInstance;

    /**
     * Setup
     *
     */
    protected function setUp(): void
    {
        $this->tagDomainModelInstance = new Tag();
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
        $title = 'Tag title';
        $this->tagDomainModelInstance->setTitle($title);
        $this->assertEquals($title, $this->tagDomainModelInstance->getTitle());
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
        $time = new \DateTime('now');
        $this->tagDomainModelInstance->setCrdate($time);
        $this->assertEquals($time, $this->tagDomainModelInstance->getCrdate());
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
        $time = new \DateTime('now');
        $this->tagDomainModelInstance->setTstamp($time);
        $this->assertEquals($time, $this->tagDomainModelInstance->getTstamp());
    }
}
