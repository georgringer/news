<?php

namespace GeorgRinger\News\Controller;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Base controller
 */
class NewsBaseController extends ActionController
{

    /**
     * Initializes the view before invoking an action method.
     * Override this method to solve assign variables common for all actions
     * or prepare the view in another way before the action is called.
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view The view to be initialized
     *
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
        $view->assign('emConfiguration', GeneralUtility::makeInstance(EmConfiguration::class));
        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            $view->assign('pageData', $GLOBALS['TSFE']->page);
        }
        parent::initializeView($view);
    }

//    /**
//     * @param RequestInterface $request
//     * @param ResponseInterface $response
//     * @throws \Exception
//     */
//    public function processRequest(RequestInterface $request, ResponseInterface $response)
//    {
//        try {
//            parent::processRequest($request, $response);
//        } catch (\Exception $exception) {
//            $this->handleKnownExceptionsElseThrowAgain($exception);
//        }
//    }

    /**
     * @param \Exception $exception
     *
     * @throws \Exception
     *
     * @return void
     */
    private function handleKnownExceptionsElseThrowAgain(\Exception $exception): void
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
     */
    protected function handleNoNewsFoundError($configuration): string
    {
        if (empty($configuration)) {
            return '';
        }
        $options = GeneralUtility::trimExplode(',', $configuration, true);

        switch ($options[0]) {
            case 'redirectToListView':
                $this->redirect('list');
                break;
            case 'redirectToPage':
                if (count($options) === 1 || count($options) > 3) {
                    $msg = sprintf(
                        'If error handling "%s" is used, either 2 or 3 arguments, split by "," must be used',
                        $options[0]
                    );
                    throw new \InvalidArgumentException($msg);
                }
                $this->uriBuilder->reset();
                $this->uriBuilder->setTargetPageUid($options[1]);
                $this->uriBuilder->setCreateAbsoluteUri(true);
                if (GeneralUtility::getIndpEnv('TYPO3_SSL')) {
                    $this->uriBuilder->setAbsoluteUriScheme('https');
                }
                $url = $this->uriBuilder->build();

                if (isset($options[2])) {
                    $this->redirectToUri($url, 0, (int)$options[2]);
                } else {
                    $this->redirectToUri($url);
                }

                break;
            case 'pageNotFoundHandler':
                $typo3Information = GeneralUtility::makeInstance(Typo3Version::class);
                if ($typo3Information->getMajorVersion() === 9) {
                    $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction($GLOBALS['TYPO3_REQUEST'], 'No news entry found.');
                    throw new ImmediateResponseException($response);
                }
                    $message = 'No news entry found!';
                    $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
                        $GLOBALS['TYPO3_REQUEST'],
                        $message
                    );
                    throw new ImmediateResponseException($response, 1590468229);

                break;
            case 'showStandaloneTemplate':
                if (isset($options[2])) {
                    $statusCode = constant(HttpUtility::class . '::HTTP_STATUS_' . $options[2]);
                } else {
                    $statusCode = HttpUtility::HTTP_STATUS_404;
                }
                HttpUtility::setResponseCode($statusCode);

                $this->getTypoScriptFrontendController()->set_no_cache('News record not found');

                $standaloneTemplate = GeneralUtility::makeInstance(StandaloneView::class);
                $standaloneTemplate->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($options[1]));
                return $standaloneTemplate->render();
                break;
            default:
                return '';
        }
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
