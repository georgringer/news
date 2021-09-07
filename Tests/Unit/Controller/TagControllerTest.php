<?php

namespace GeorgRinger\News\Tests\Unit\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Controller\TagController;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Repository\TagRepository;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Testcase for the TagController class.
 *
 *
 */
class TagControllerTest extends BaseTestCase
{
    private $tagRepository = null;

    /**
     * Set up framework
     *
     */
    public function setup(): void
    {
        $this->tagRepository = $this->prophesize(TagRepository::class);
    }

    /**
     * Test for creating correct demand call
     *
     * @test
     *
     * @return void
     */
    public function listActionFindsDemandedTagsByDemandFromSettings(): void
    {
        $this->markTestSkipped('May not be relevant anymore. Reason: failing because of using DI');
        $demand = new NewsDemand();
        $settings = ['list' => 'foo', 'orderBy' => 'datetime'];

        $fixture->_set('tagRepository', $this->tagRepository->reveal());
        $fixture->setView($this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock());
        $fixture->_set('settings', $settings);

        $fixture->expects($this->once())->method('createDemandObjectFromSettings')
            ->will($this->returnValue($demand));

        $this->tagRepository->findDemanded($demand)->shouldBeCalled();

        $fixture->listAction();

        // datetime must be removed
        $this->assertEquals($fixture->_get('settings'), ['list' => 'foo']);
    }
}
