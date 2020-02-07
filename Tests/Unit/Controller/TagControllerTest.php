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
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Testcase for the TagController class.
 *
 *
 */
class TagControllerTest extends BaseTestCase
{

    /**
     * @var TagController
     */
    private $fixture = null;

    /**
     */
    private $tagRepository = null;

    /**
     * Set up framework
     *
     */
    public function setup(): void
    {
        $this->fixture = new TagController();

        $this->tagRepository = $this->prophesize(TagRepository::class);
    }


    /**
     * Test for creating correct demand call
     *
     * @test
     */
    public function listActionFindsDemandedTagsByDemandFromSettings()
    {
        $demand = new NewsDemand();
        $settings = ['list' => 'foo', 'orderBy' => 'datetime'];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', ['dispatch'], [], '', false);

        $fixture = $this->getAccessibleMock(TagController::class, ['createDemandObjectFromSettings', 'emitActionSignal']);
        $fixture->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $fixture->_set('tagRepository', $this->tagRepository->reveal());
        $fixture->injectConfigurationManager($this->getMockBuilder('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface')->getMock());
        $fixture->setView($this->getMockBuilder('TYPO3\CMS\Fluid\View\TemplateView')->disableOriginalConstructor()->getMock());
        $fixture->_set('settings', $settings);

        $fixture->expects($this->once())->method('createDemandObjectFromSettings')
            ->will($this->returnValue($demand));
        $fixture->expects($this->once())->method('emitActionSignal')->will($this->returnValue([]));

        $this->tagRepository->findDemanded($demand)->shouldBeCalled();

        $fixture->listAction();

        // datetime must be removed
        $this->assertEquals($fixture->_get('settings'), ['list' => 'foo']);
    }
}
