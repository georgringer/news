<?php
namespace GeorgRinger\News\Controller;

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
use GeorgRinger\News\Utility\EmConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;

/**
 * Base controller
 *
 */
class NewsBaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Initializes the view before invoking an action method.
     * Override this method to solve assign variables common for all actions
     * or prepare the view in another way before the action is called.
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view The view to be initialized
     * @return void
     */
    protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
        $view->assign('emConfiguration', EmConfiguration::getSettings());
        parent::initializeView($view);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws \Exception
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        try {
            parent::processRequest($request, $response);
        } catch (\Exception $exception) {
            $this->handleKnownExceptionsElseThrowAgain($exception);
        }
    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    private function handleKnownExceptionsElseThrowAgain(\Exception $exception)
    {
        $previousException = $exception->getPrevious();

        if (
            $this->actionMethodName === 'detailAction'
            && $previousException instanceof \TYPO3\CMS\Extbase\Property\Exception
            && isset($this->settings['detail']['errorHandling'])
        ) {
            $this->handleNoNewsFoundError($this->settings['detail']['errorHandling']);
        } else {
            throw $exception;
        }
    }

    /**
     * Error handling if no news entry is found
     *
     * @param string $configuration configuration what will be done
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function handleNoNewsFoundError($configuration)
    {
        if (empty($configuration)) {
            return;
        }

        $configuration = GeneralUtility::trimExplode(',', $configuration, true);

        switch ($configuration[0]) {
            case 'redirectToListView':
                $this->redirect('list');
                break;
            case 'redirectToPage':
                if (count($configuration) === 1 || count($configuration) > 3) {
                    $msg = sprintf('If error handling "%s" is used, either 2 or 3 arguments, split by "," must be used',
                        $configuration[0]);
                    throw new \InvalidArgumentException($msg);
                }
                $this->uriBuilder->reset();
                $this->uriBuilder->setTargetPageUid($configuration[1]);
                $this->uriBuilder->setCreateAbsoluteUri(true);
                if (GeneralUtility::getIndpEnv('TYPO3_SSL')) {
                    $this->uriBuilder->setAbsoluteUriScheme('https');
                }
                $url = $this->uriBuilder->build();

                if (isset($configuration[2])) {
                    $this->redirectToUri($url, 0, (int)$configuration[2]);
                } else {
                    $this->redirectToUri($url);
                }

                break;
            case 'pageNotFoundHandler':
                $GLOBALS['TSFE']->pageNotFoundAndExit('No news entry found.');
                break;
            default:
                // Do nothing, it might be handled in the view.
        }
    }

    /**
     * Emits signal for various actions
     *
     * @param string $classPart last part of the class name
     * @param string $signalName name of the signal slot
     * @param array $signalArguments arguments for the signal slot
     *
     * @return array
     */
    protected function emitActionSignal($classPart, $signalName, array $signalArguments)
    {
        $signalArguments['extendedVariables'] = [];
        return $this->signalSlotDispatcher->dispatch('GeorgRinger\\News\\Controller\\' . $classPart, $signalName,
            $signalArguments);
    }
}
