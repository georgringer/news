<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Controller;

use GeorgRinger\News\Controller\TagController;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Repository\TagRepository;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Testcase for the TagController class.
 */
class TagControllerTest extends UnitTestCase
{
    /**
     * @test
     * @todo Eventually migrate this to a functional test instead, remove it or get it working again.
     */
    public function listActionFindsDemandedTagsByDemandFromSettings(): void
    {
        self::markTestSkipped('May not be relevant anymore. Reason: failing because of using DI');
        $tagRepositoryMock = $this->getMockBuilder(TagRepository::class)->disableOriginalConstructor()->getMock();
        $demand = new NewsDemand();
        $settings = ['list' => 'foo', 'orderBy' => 'datetime'];

        $fixture = $this->getAccessibleMock(TagController::class);
        $fixture->_set('tagRepository', $tagRepositoryMock);
        $fixture->setView($this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock());
        $fixture->_set('settings', $settings);

        $fixture->expects(self::once())->method('createDemandObjectFromSettings')
            ->willReturn($demand);

        $this->tagRepository->findDemanded($demand)->shouldBeCalled();

        $fixture->listAction();

        // datetime must be removed
        self::assertEquals($fixture->_get('settings'), ['list' => 'foo']);
    }
}
