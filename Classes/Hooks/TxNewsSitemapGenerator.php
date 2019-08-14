<?php

namespace GeorgRinger\News\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use DmitryDulepov\DdGooglesitemap\Generator\AbstractSitemapGenerator;
use DmitryDulepov\DdGooglesitemap\Renderers\NewsSitemapRenderer;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

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
    protected $pidList = [];

    /**
     * Indicates sitemap type
     *
     * @var bool
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
     * @var bool
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
     */
    protected function generateSitemapContent()
    {
        if (count($this->pidList) > 0) {
            /** @var TypoScriptFrontendController $tsfe */
            $tsfe = $GLOBALS['TSFE'];
            $tsfe->sys_language_content = (int)$GLOBALS['TSFE']->config['config']['sys_language_uid'];

            $rows = $this->cObj->getRecords('tx_news_domain_model_news', [
                'selectFields' => '*',
                'pidInList' => implode(',', $this->pidList),
                'where' => $this->isNewsSitemap ? 'datetime >= ' . ($GLOBALS['EXEC_TIME'] - 48 * 60 * 60) : '',
                'orderBy' => 'datetime DESC',
                'begin' => $this->offset,
                'max' => $this->limit
            ]);

            foreach ($rows as $row) {
                $this->generateSingleLine($row);
            }

            if (empty($rows)) {
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
     * @param array $row
     */
    protected function generateSingleLine(array $row)
    {
        $forceSinglePid = null;
        if ($row['categories'] && $this->useCategorySinglePid) {
            $forceSinglePid = $this->getSinglePidFromCategory($row['uid']);
        }
        $url = $this->getNewsItemUrl($row, $forceSinglePid);
        if ($url) {
            echo $this->renderer->renderEntry($url, $row['title'], $row['datetime'],
                '', $row['keywords']);
        } else {
            echo 'xx';
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
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $categoryRecord = $queryBuilder
            ->select('title', 'single_pid')
            ->from('sys_category')
            ->leftJoin(
                'sys_category',
                'sys_category_record_mm',
                'sys_category_record_mm',
                $queryBuilder->expr()->eq('sys_category_record_mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid'))
            )
            ->where(
                $queryBuilder->expr()->eq('sys_category_record_mm.tablenames', $queryBuilder->createNamedParameter('tx_news_domain_model_news', \PDO::PARAM_STR)),
                $queryBuilder->expr()->gt('sys_category.single_pid', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('sys_category_record_mm.uid_foreign', $queryBuilder->createNamedParameter($newsId, \PDO::PARAM_INT))

            )
            ->setMaxResults(1)
            ->execute()->fetch();

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
        $configuration = $GLOBALS['TSFE']->tmpl->setup['tx_ddgooglesitemap.'];
        $link = '';
        if (is_string($configuration['tx_newsLink']) && is_array($configuration['tx_newsLink.'])) {
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $cObj->start($newsRow, 'tx_news_domain_model_news');
            $cObj->setCurrentVal($forceSinglePid ?: $this->singlePid);
            $link = $cObj->cObjGetSingle($configuration['tx_newsLink'], $configuration['tx_newsLink.']);
            unset($cObj);
        }
        $additionalParams = '';
        $skipControllerAndAction = isset($configuration['tx_news.'])
            && is_array($configuration['tx_news.'])
            && $configuration['tx_news.']['skipControllerAndAction'] == 1;
        if (!$skipControllerAndAction) {
            $additionalParams .= '&tx_news_pi1[controller]=News&tx_news_pi1[action]=detail';
        }
        if ((int)$configuration['tx_news.']['hrDate'] === 1 && $newsRow['datetime'] > 0) {
            if (!empty($configuration['tx_news.']['hrDate.']['day'])) {
                $additionalParams .= '&tx_news_pi1[day]=' . date($configuration['tx_news.']['hrDate.']['day'], $newsRow['datetime']);
            }
            if (!empty($configuration['tx_news.']['hrDate.']['month'])) {
                $additionalParams .= '&tx_news_pi1[month]=' . date($configuration['tx_news.']['hrDate.']['month'], $newsRow['datetime']);
            }
            if (!empty($configuration['tx_news.']['hrDate.']['year'])) {
                $additionalParams .= '&tx_news_pi1[year]=' . date($configuration['tx_news.']['hrDate.']['year'], $newsRow['datetime']);
            }
        }

        if ($link === '') {
            $newsType = (int)$newsRow['type'];
            $conf = [
                'additionalParams' => '&tx_news_pi1[news]=' . $newsRow['uid'] . $additionalParams,
                'forceAbsoluteUrl' => 1,
                'parameter' => $forceSinglePid ?: $this->singlePid,
                'returnLast' => 'url',
                'useCacheHash' => true,
            ];
            if ($newsType === 1 && !empty($newsRow['internalurl'])) {
                $conf['additionalParams'] = $additionalParams;
                $conf['parameter'] = $newsRow['internalurl'];
            } elseif ($newsType === 2 && !empty($newsRow['externalurl'])) {
                $conf['additionalParams'] = $additionalParams;
                $conf['parameter'] = $newsRow['externalurl'];
            }

            $link = htmlspecialchars($this->cObj->typoLink('', $conf));
        }
        return $link;
    }

    /**
     * Checks that page list is in the rootline of the current page and excludes
     * pages that are outside of the rootline.
     *
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
     * @param int $pid Page id to check
     * @return bool true if page is in the root line
     */
    protected function isInRootline($pid)
    {
        /** @var TypoScriptFrontendController $tsfe */
        $tsfe = $GLOBALS['TSFE'];
        if (isset($tsfe->config['config']['tx_ddgooglesitemap_skipRootlineCheck'])) {
            $skipRootlineCheck = $tsfe->config['config']['tx_ddgooglesitemap_skipRootlineCheck'];
        } else {
            $skipRootlineCheck = $tsfe->tmpl->setup['tx_ddgooglesitemap.']['skipRootlineCheck'];
        }
        if ($skipRootlineCheck) {
            $result = true;
        } else {
            $result = false;
            $rootPid = intval($tsfe->tmpl->setup['tx_ddgooglesitemap.']['forceStartPid']);
            if ($rootPid == 0) {
                $rootPid = $tsfe->id;
            }
            try {
                $rootline = $tsfe->sys_page->getRootLine($pid);
            } catch (\Exception $e) {
                $rootline = [];
            }
            foreach ($rootline as $row) {
                if ($row['uid'] == $rootPid) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }
}
