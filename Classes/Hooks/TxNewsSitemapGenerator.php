<?php

namespace GeorgRinger\News\Hooks;

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

use DmitryDulepov\DdGooglesitemap\Generator\AbstractSitemapGenerator;
use DmitryDulepov\DdGooglesitemap\Renderers\NewsSitemapRenderer;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * This class implements news sitemap
 * (http://www.google.com/support/webmasters/bin/answer.py?hl=en-nz&answer=42738)
 * for Google.
 *
 * The following URL parameters are expected:
 * - sitemap=txnews
 * - singlePid=<uid of the "single" tx_news view>
 * - pidList=<comma-separated list of storage pids>
 * All pids must be in the rootline of the current pid. The safest way is to call
 * this site map from the root page of the site:
 * http://example.com/?eID=dd_googlesitemap&sitemap=txnews&singlePid=100&pidList=101,102,115
 *
 * If you need to show news on different single view pages, make several sitemaps
 * (it is possible with Google).
 */
class TxNewsSitemapGenerator extends AbstractSitemapGenerator
{

    /**
     * List of storage pages where news items are located
     *
     * @var    array
     */
    protected $pidList = array();

    /**
     * Indicates sitemap type
     *
     * @var boolean
     */
    protected $isNewsSitemap;

    /**
     * Single view page
     *
     * @var int
     */
    protected $singlePid;

    /**
     * If true, try to get the single pid for a news item from its (first) category with fallback to $this->singlePid
     *
     * @var boolean
     */
    protected $useCategorySinglePid;

    /**
     * Creates an instance of this class
     */
    public function __construct()
    {
        $this->isNewsSitemap = (GeneralUtility::_GET('type') === 'news');
        if ($this->isNewsSitemap) {
            $this->rendererClass = NewsSitemapRenderer::class;
        }
        parent::__construct();

        $singlePid = intval(GeneralUtility::_GP('singlePid'));
        $this->singlePid = $singlePid && $this->isInRootline($singlePid) ? $singlePid : $GLOBALS['TSFE']->id;
        $this->useCategorySinglePid = (bool)GeneralUtility::_GP('useCategorySinglePid');

        $this->validateAndCreatePageList();
    }

    /**
     * Generates news site map.
     *
     * @return void
     */
    protected function generateSitemapContent()
    {
        if (count($this->pidList) > 0) {
            $languageCondition = '';

            $language = GeneralUtility::_GP('L');
            if (MathUtility::canBeInterpretedAsInteger($language)) {
                $languageCondition = ' AND sys_language_uid=' . $language;
            }

            $res = $this->getDatabaseConnection()->exec_SELECTquery('*',
                'tx_news_domain_model_news', 'pid IN (' . implode(',', $this->pidList) . ')' .
                ($this->isNewsSitemap ? ' AND crdate>=' . ($GLOBALS['EXEC_TIME'] - 48 * 60 * 60) : '') .
                $languageCondition .
                $this->cObj->enableFields('tx_news_domain_model_news'), '', 'datetime DESC',
                $this->offset . ',' . $this->limit
            );
            $rowCount = $this->getDatabaseConnection()->sql_num_rows($res);
            while (false !== ($row = $this->getDatabaseConnection()->sql_fetch_assoc($res))) {
                $forceSinglePid = null;
                if ($row['categories'] && $this->useCategorySinglePid) {
                    $forceSinglePid = $this->getSinglePidFromCategory($row['uid']);
                }
                if (($url = $this->getNewsItemUrl($row, $forceSinglePid))) {
                    echo $this->renderer->renderEntry($url, $row['title'], $row['datetime'],
                        '', $row['keywords']);
                }
            }
            $this->getDatabaseConnection()->sql_free_result($res);

            if ($rowCount === 0) {
                echo '<!-- It appears that there are no tx_news entries. If your ' .
                    'news storage sysfolder is outside of the rootline, you may ' .
                    'want to use the dd_googlesitemap.skipRootlineCheck=1 TS ' .
                    'setup option. Beware: it is insecure and may cause certain ' .
                    'undesired effects! Better move your news sysfolder ' .
                    'inside the rootline! -->';
            }
        }
    }

    /**
     * Obtains a pid for the single view from the category.
     *
     * @param int $newsId
     * @return int|null
     */
    protected function getSinglePidFromCategory($newsId)
    {
        $res = $this->getDatabaseConnection()->exec_SELECT_mm_query(
            'sys_category.single_pid',
            'tx_news_domain_model_news',
            'sys_category_record_mm',
            'sys_category',
            ' AND sys_category_record_mm.uid_local = ' . intval($newsId)
        );
        $categoryRecord = $this->getDatabaseConnection()->sql_fetch_assoc($res);

        return $categoryRecord['single_pid'] ?: null;
    }

    /**
     * Creates a link to the news item
     *
     * @param array $newsRow News item
     * @param  int $forceSinglePid Single View page for this news item
     * @return string
     */
    protected function getNewsItemUrl($newsRow, $forceSinglePid = null)
    {
        $link = '';
        if (is_string($GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['tx_newsLink']) && is_array($GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['tx_newsLink'])) {
            $cObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
            /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj */
            $cObj->start($newsRow, 'tx_news_domain_model_news');
            $cObj->setCurrentVal($forceSinglePid ?: $this->singlePid);
            $link = $cObj->cObjGetSingle($GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['tx_newsLink'],
                $GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['tx_newsLink']);
            unset($cObj);
        }
        $skipControllerAndAction = isset($GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['tx_news.'])
            && is_array($GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['tx_news.'])
            && $GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['tx_news.']['skipControllerAndAction'] == 1;

        if ($link == '') {
            $conf = array(
                'additionalParams' => (!$skipControllerAndAction ? '&tx_news_pi1[controller]=News&tx_news_pi1[action]=detail' : '') . '&tx_news_pi1[news]=' . $newsRow['uid'],
                'forceAbsoluteUrl' => 1,
                'parameter' => $forceSinglePid ?: $this->singlePid,
                'returnLast' => 'url',
                'useCacheHash' => true,
            );
            $link = htmlspecialchars($this->cObj->typoLink('', $conf));
        }
        return $link;
    }

    /**
     * Checks that page list is in the rootline of the current page and excludes
     * pages that are outside of the rootline.
     *
     * @return    void
     */
    protected function validateAndCreatePageList()
    {
        // Get pages
        $pidList = GeneralUtility::intExplode(',', GeneralUtility::_GP('pidList'));
        // Check pages
        foreach ($pidList as $pid) {
            if ($pid && $this->isInRootline($pid)) {
                $this->pidList[$pid] = $pid;
            }
        }
    }

    /**
     * Check if supplied page id and current page are in the same root line
     *
     * @param    int $pid Page id to check
     * @return    boolean    true if page is in the root line
     */
    protected function isInRootline($pid)
    {
        if (isset($GLOBALS['TSFE']->config['config']['tx_ddgooglesitemap_skipRootlineCheck'])) {
            $skipRootlineCheck = $GLOBALS['TSFE']->config['config']['tx_ddgooglesitemap_skipRootlineCheck'];
        } else {
            $skipRootlineCheck = $GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['skipRootlineCheck'];
        }
        if ($skipRootlineCheck) {
            $result = true;
        } else {
            $result = false;
            $rootPid = intval($GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.']['forceStartPid']);
            if ($rootPid == 0) {
                $rootPid = $GLOBALS['TSFE']->id;
            }
            $rootline = $GLOBALS['TSFE']->sys_page->getRootLine($pid);
            foreach ($rootline as $row) {
                if ($row['uid'] == $rootPid) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}