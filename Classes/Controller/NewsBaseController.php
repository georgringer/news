<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Controller;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class NewsBaseController extends ActionController
{
    protected function initializeView($view)
    {
        $contentObjectData = $this->request->getAttribute('currentContentObject');
        $view->assign('contentObjectData', $contentObjectData ? $contentObjectData->data : null);
        $view->assign('emConfiguration', GeneralUtility::makeInstance(EmConfiguration::class));
        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            $view->assign('pageData', $GLOBALS['TSFE']->page);
        }
    }

    /**
     * Error handling if no news entry is found
     *
     * @param string $configuration configuration what will be done
     * @throws \InvalidArgumentException
     */
    protected function handleNoNewsFoundError(string $configuration): ?ResponseInterface
    {
        if (empty($configuration)) {
            return null;
        }
        $options = GeneralUtility::trimExplode(',', $configuration, true);

        switch ($options[0]) {
            case 'redirectToListView':
                return $this->redirect('list');
            case 'redirectToPage':
                if (count($options) === 1 || count($options) > 3) {
                    $msg = sprintf(
                        'If error handling "%s" is used, either 2 or 3 arguments, split by "," must be used',
                        $options[0]
                    );
                    throw new \InvalidArgumentException($msg, 7238087293);
                }
                $this->uriBuilder->reset();
                $this->uriBuilder->setTargetPageUid($options[1]);
                $this->uriBuilder->setCreateAbsoluteUri(true);
                if (GeneralUtility::getIndpEnv('TYPO3_SSL')) {
                    $this->uriBuilder->setAbsoluteUriScheme('https');
                }
                $url = $this->uriBuilder->build();

                if (isset($options[2])) {
                    return $this->responseFactory->createResponse((int)$options[2])->withHeader('Location', $url);
                }
                return $this->responseFactory->createResponse()->withHeader('Location', $url);
            case 'pageNotFoundHandler':
                $message = 'No news entry found!';
                $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
                    $GLOBALS['TYPO3_REQUEST'],
                    $message
                );
                throw new ImmediateResponseException($response, 1590468229);
            case 'showStandaloneTemplate':
                $statusCode = (int)($options[2] ?? 404);

                $this->getTypoScriptFrontendController()->set_no_cache('News record not found');

                $standaloneTemplate = GeneralUtility::makeInstance(StandaloneView::class);
                $standaloneTemplate->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($options[1]));

                return $this->responseFactory->createResponse($statusCode)
                    ->withHeader('Content-Type', 'text/html; charset=utf-8')
                    ->withBody($this->streamFactory->createStream($standaloneTemplate->render()));
        }
        return null;
    }

    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
