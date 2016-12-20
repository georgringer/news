<?php

namespace GeorgRinger\News\Backend;

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
use Exception;
use GeorgRinger\News\Utility\EmConfiguration;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\DataHandling\DataHandler as DataHandlerCore;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Ajax response for the custom suggest receiver
 *
 */
class TagEndPoint
{

    const TAG = 'tx_news_domain_model_tag';
    const NEWS = 'tx_news_domain_model_news';
    const LL_PATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:tag_suggest_';

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @return Response
     */
    public function create(ServerRequestInterface $request, Response $response)
    {
        try {
            $item = isset($request->getParsedBody()['item']) ? $request->getParsedBody()['item'] : $request->getQueryParams()['item'];

            if (empty($item)) {
                throw new Exception('error_no-tag');
            }

            $newsUid = isset($request->getParsedBody()['newsid']) ? $request->getParsedBody()['newsid'] : $request->getQueryParams()['newsid'];
            if ((int)$newsUid === 0) {
                throw new Exception('error_no-newsid');
            }

            // Get tag uid
            $newTagId = $this->getTagUid($item, $newsUid);

            $content = [
                $newTagId,
                $item,
                self::TAG,
                self::NEWS,
                'tags',
                'data[tx_news_domain_model_news][' . $newsUid . '][tags]',
                $newsUid
            ];
            $response->getBody()->write(implode('-', $content));
        } catch (Exception $e) {
            $message = $GLOBALS['LANG']->sL(self::LL_PATH . $e->getMessage());
            throw new \RuntimeException($message);
        }
        return $response;
    }

    /**
     * Get the uid of the tag, either bei inserting as new or get existing
     *
     * @param string $title title
     * @param int $newsUid news uid
     * @return int
     * @throws Exception
     */
    protected function getTagUid($title, $newsUid)
    {
        // Get configuration from EM
        $configuration = EmConfiguration::getSettings();

        $pid = $configuration->getTagPid();
        if ($pid === 0) {
            $pid = $this->getTagPidFromTsConfig($newsUid);
        }

        if ($pid === 0) {
            throw new Exception('error_no-pid-defined');
        }

        $record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
            '*',
            self::TAG,
            'deleted=0 AND pid=' . $pid .
            ' AND title=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($title, self::TAG)
        );
        if (isset($record['uid'])) {
            $tagUid = $record['uid'];
        } else {
            $tcemainData = [
                self::TAG => [
                    'NEW' => [
                        'pid' => $pid,
                        'title' => $title
                    ]
                ]
            ];

            $dataHandler = GeneralUtility::makeInstance(DataHandlerCore::class);
            $dataHandler->start($tcemainData, []);
            $dataHandler->process_datamap();

            $tagUid = $dataHandler->substNEWwithIDs['NEW'];
        }

        if ($tagUid == 0) {
            throw new Exception('error_no-tag-created');
        }

        return $tagUid;
    }

    /**
     * Get pid for tags from TsConfig
     *
     * @param int $newsUid uid of current news record
     * @return int
     */
    protected function getTagPidFromTsConfig($newsUid)
    {
        $pid = 0;

        $newsRecord = BackendUtilityCore::getRecord('tx_news_domain_model_news', (int)$newsUid);

        $pagesTsConfig = BackendUtilityCore::getPagesTSconfig($newsRecord['pid']);
        if (isset($pagesTsConfig['tx_news.']) && isset($pagesTsConfig['tx_news.']['tagPid'])) {
            $pid = (int)$pagesTsConfig['tx_news.']['tagPid'];
        }

        return $pid;
    }
}
