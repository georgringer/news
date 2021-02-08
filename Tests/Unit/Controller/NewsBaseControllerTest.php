<?php

namespace GeorgRinger\News\Tests\Unit\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Controller\NewsBaseController;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Testcase for the GeorgRinger\\News\\Controller\\NewsBaseController class.
 *
 *
 */
class NewsBaseControllerTest extends BaseTestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TypoScriptFrontendController|AccessibleMockObjectInterface
     */
    protected $tsfe = null;

    /**
     * Set up
     */
    public function setup(): void
    {
        $this->tsfe = $this->getAccessibleMock(TypoScriptFrontendController::class, ['pageNotFoundAndExit'], [], '', false);
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * @test
     */
    public function emptyNoNewsFoundConfigurationReturnsNull()
    {
        $mockedController = $this->getAccessibleMock(NewsBaseController::class, ['dummy']);
        $result = $mockedController->_call('handleNoNewsFoundError', '');
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function invalidNoNewsFoundConfigurationReturnsNull()
    {
        $mockedController = $this->getAccessibleMock(NewsBaseController::class, ['dummy']);
        $result = $mockedController->_call('handleNoNewsFoundError', 'fo');
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationRedirectsToListView()
    {
        $mock = $this->getAccessibleMock(NewsBaseController::class, ['redirect']);
        $mock->expects($this->once())
            ->method('redirect')->with('list');
        $mock->_call('handleNoNewsFoundError', 'redirectToListView');
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationCallsPageNotFoundHandler()
    {
        $mock = $this->getAccessibleMock(
            NewsBaseController::class,
            ['dummy']
        );

        $typo3Information = GeneralUtility::makeInstance(Typo3Version::class);
        if ($typo3Information->getMajorVersion() === 9) {
            $this->tsfe->expects($this->once())
                ->method('pageNotFoundAndExit');
        } else {
            $GLOBALS['TYPO3_REQUEST'] = new ServerRequest('/');

            $mockedErrorController = $this->getAccessibleMock(
                ErrorController::class,
                ['pageNotFoundAction'],
                [],
                '',
                false
            );
            $mockedErrorController->expects($this->once())->method('pageNotFoundAction');
            GeneralUtility::addInstance(ErrorController::class, $mockedErrorController);

            $this->expectException(ImmediateResponseException::class);
        }
        $mock->_call('handleNoNewsFoundError', 'pageNotFoundHandler');
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationThrowsExceptionWithTooLessRedirectToPageOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getAccessibleMock(
            NewsBaseController::class,
            ['dummy']
        );
        $mock->_call('handleNoNewsFoundError', 'redirectToPage');
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationThrowsExceptionWithTooManyRedirectToPageOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getAccessibleMock(
            NewsBaseController::class,
            ['dummy']
        );
        $mock->_call('handleNoNewsFoundError', 'redirectToPage,argumentOne,argumentTwo,argumentThree');
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationRedirectsToCorrectPage()
    {
        $mockController = $this->getAccessibleMock(NewsBaseController::class, ['redirectToUri']);

        $mockUriBuilder = $this->getAccessibleMock(UriBuilder::class);
        $mockController->_set('uriBuilder', $mockUriBuilder);

        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')->with('123');
        $mockUriBuilder->expects($this->once())
            ->method('build');

        $mockController->_call('handleNoNewsFoundError', 'redirectToPage,123');
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationRedirectsToCorrectPageAndStatus()
    {
        $mockController = $this->getAccessibleMock(NewsBaseController::class, ['redirectToUri']);

        $mockUriBuilder = $this->getAccessibleMock(UriBuilder::class);
        $mockController->_set('uriBuilder', $mockUriBuilder);

        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')->with('456');
        $mockUriBuilder->expects($this->once())
            ->method('build');
        $mockController->expects($this->once())
            ->method('redirectToUri')->with(null, 0, 301);
        $mockController->_call('handleNoNewsFoundError', 'redirectToPage,456,301');
    }

    /**
     * @test
     */
    public function signalSlotGetsEmitted()
    {
        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch'], [], '', false);
        $mockedController = $this->getAccessibleMock(NewsBaseController::class, ['dummy']);
        $mockedController->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $classPart = 'FoController';
        $signalArguments = ['fo' => 'bar', 'extendedVariables' => []];
        $name = 'foAction';

        $mockedSignalSlotDispatcher->expects($this->once())
            ->method('dispatch')->with('GeorgRinger\\News\\Controller\\' . $classPart, $name, $signalArguments);

        $mockedController->_call('emitActionSignal', $classPart, $name, $signalArguments);
    }
}
