<?php

namespace GeorgRinger\News\Tests\Unit\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Testcase for the GeorgRinger\\News\\Controller\\NewsBaseController class.
 *
 *
 */
class NewsBaseControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $tsfe = null;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->tsfe = $this->getAccessibleMock(TypoScriptFrontendController::class, ['pageNotFoundAndExit'], [], '', false);
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * @test
     */
    public function emptyNoNewsFoundConfigurationReturnsNull()
    {
        $mockedController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', ['dummy']);
        $result = $mockedController->_call('handleNoNewsFoundError', []);
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationRedirectsToListView()
    {
        $mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
            ['redirectToUri']);
        $mock->expects($this->once())
            ->method('redirectToUri')->with('built-url');

        $mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder', ['build']);
        $mock->_set('uriBuilder', $mockUriBuilder);

        $mockUriBuilder->expects($this->once())
            ->method('reset');
        $mockUriBuilder->expects($this->once())
            ->method('setCreateAbsoluteUri')->with(true);
        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')->with(123);
        $mockUriBuilder->expects($this->once())
            ->method('build')->willReturn('built-url');

        $mock->_call('handleNoNewsFoundError', ['backPid' => 123, 'detail' => ['errorHandling' => 'redirectToListView']]);
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationCallsPageNotFoundHandler()
    {
        $mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
            ['dummy']);

        $this->tsfe->expects($this->once())
            ->method('pageNotFoundAndExit');
        $mock->_call('handleNoNewsFoundError', ['detail' => ['errorHandling' => 'pageNotFoundHandler']]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function NoNewsFoundConfigurationThrowsExceptionWithTooLessRedirectToPageOptions()
    {
        $mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
            ['dummy']);
        $mock->_call('handleNoNewsFoundError', ['detail' => ['errorHandling' => 'redirectToPage']]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function NoNewsFoundConfigurationThrowsExceptionWithTooManyRedirectToPageOptions()
    {
        $mock = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController',
            ['dummy']);
        $mock->_call('handleNoNewsFoundError', ['detail' => ['errorHandling' => 'redirectToPage,argumentOne,argumentTwo,argumentThree']]);
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationRedirectsToCorrectPage()
    {
        $mockController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', ['redirectToUri']);

        $mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
        $mockController->_set('uriBuilder', $mockUriBuilder);

        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')->with('123');
        $mockUriBuilder->expects($this->once())
            ->method('build');

        $mockController->_call('handleNoNewsFoundError', ['detail' => ['errorHandling' => 'redirectToPage,123']]);
    }

    /**
     * @test
     */
    public function NoNewsFoundConfigurationRedirectsToCorrectPageAndStatus()
    {
        $mockController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', ['redirectToUri']);

        $mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
        $mockController->_set('uriBuilder', $mockUriBuilder);

        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')->with('456');
        $mockUriBuilder->expects($this->once())
            ->method('build');
        $mockController->expects($this->once())
            ->method('redirectToUri')->with(null, 0, 301);
        $mockController->_call('handleNoNewsFoundError', ['detail' => ['errorHandling' => 'redirectToPage,456,301']]);
    }

    /**
     * @test
     */
    public function signalSlotGetsEmitted()
    {
        $mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', ['dispatch']);
        $mockedController = $this->getAccessibleMock('GeorgRinger\\News\\Controller\\NewsBaseController', ['dummy']);
        $mockedController->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $classPart = 'FoController';
        $signalArguments = ['fo' => 'bar', 'extendedVariables' => []];
        $name = 'foAction';

        $mockedSignalSlotDispatcher->expects($this->once())
            ->method('dispatch')->with('GeorgRinger\\News\\Controller\\' . $classPart, $name, $signalArguments);

        $mockedController->_call('emitActionSignal', $classPart, $name, $signalArguments);
    }
}
