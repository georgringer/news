<?php

declare(strict_types=1);

namespace GeorgRinger\News\Seo;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\WorkspaceAspect;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Seo\XmlSitemap\AbstractXmlSitemapDataProvider;
use TYPO3\CMS\Seo\XmlSitemap\Exception\MissingConfigurationException;

/**
 * Generate sitemap for news records
 */
class NewsXmlSitemapDataProvider extends AbstractXmlSitemapDataProvider
{

    /**
     * @param ServerRequestInterface $request
     * @param string $key
     * @param array $config
     * @param ContentObjectRenderer|null $cObj
     * @throws MissingConfigurationException
     */
    public function __construct(ServerRequestInterface $request, string $key, array $config = [], ContentObjectRenderer $cObj = null)
    {
        parent::__construct($request, $key, $config, $cObj);

        $this->generateItems();
    }

    /**
     * @throws MissingConfigurationException
     */
    public function generateItems(): void
    {
        $table = 'tx_news_domain_model_news';

        $pids = !empty($this->config['pid']) ? GeneralUtility::intExplode(',', $this->config['pid']) : [];
        $lastModifiedField = $this->config['lastModifiedField'] ?? 'tstamp';
        $sortField = $this->config['sortField'] ?? 'datetime';
        $forGoogleNews = $this->config['googleNews'] ?? false;

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $constraints = [];
        if (!empty($GLOBALS['TCA'][$table]['ctrl']['languageField'])) {
            $constraints[] = $queryBuilder->expr()->in(
                $GLOBALS['TCA'][$table]['ctrl']['languageField'],
                [
                    -1, // All languages
                    $this->getLanguageId()  // Current language
                ]
            );
        }

        if (!empty($pids)) {
            $recursiveLevel = isset($this->config['recursive']) ? (int)$this->config['recursive'] : 0;
            if ($recursiveLevel) {
                $newList = [];
                foreach ($pids as $pid) {
                    $list = $this->cObj->getTreeList($pid, $recursiveLevel);
                    if ($list) {
                        $newList = array_merge($newList, explode(',', $list));
                    }
                }
                $pids = array_merge($pids, $newList);
            }

            $constraints[] = $queryBuilder->expr()->in('pid', $pids);
        }

        if ($forGoogleNews) {
            $constraints[] = $queryBuilder->expr()->gt($sortField, (new \DateTime('-2 days'))->getTimestamp());
        }

        if (!empty($this->config['excludedTypes'])) {
            $excludedTypes = GeneralUtility::trimExplode(',', $this->config['excludedTypes'], true);
            if (!empty($excludedTypes)) {
                $constraints[] = $queryBuilder->expr()->notIn(
                    'type',
                    $queryBuilder->createNamedParameter($excludedTypes, Connection::PARAM_STR_ARRAY)
                );
            }
        }

        if (!empty($this->config['additionalWhere'])) {
            $constraints[] = $this->config['additionalWhere'];
        }

        $queryBuilder->getRestrictions()->add(
            GeneralUtility::makeInstance(WorkspaceRestriction::class, $this->getCurrentWorkspaceAspect()->getId())
        );

        $queryBuilder->select('*')
            ->from($table);

        if (!empty($constraints)) {
            $queryBuilder->where(
                ...$constraints
            );
        }

        $rows = $queryBuilder->orderBy($sortField, $forGoogleNews ? 'DESC' : 'ASC')
            ->execute()
            ->fetchAll();

        foreach ($rows as $row) {
            $this->items[] = [
                'data' => $row,
                'lastMod' => (int)$row[$lastModifiedField],
                'priority' => 0.5
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function defineUrl(array $data): array
    {
        $pageId = $this->config['url']['pageId'] ?? $GLOBALS['TSFE']->id;
        if ($this->config['url']['useCategorySinglePid'] && $pageIdFromCategory = $this->getSinglePidFromCategory($data['data']['uid'])) {
            $pageId = $pageIdFromCategory;
        }

        $additionalParams = [];
        $additionalParams = $this->getUrlFieldParameterMap($additionalParams, $data['data']);
        $additionalParams = $this->getUrlAdditionalParams($additionalParams);

        if (!empty($this->config['url']['hrDate']) && !empty($data['data']['datetime'])) {
            // adjust timezone (database field is UTC)
            $timezoneCorrectedDatetime = (int)$data['data']['datetime'] + date('Z', (int)$data['data']['datetime']);
            $dateTime = \DateTime::createFromFormat('U', (string)$timezoneCorrectedDatetime);
            if (!empty($this->config['url']['hrDate']['day'])) {
                $additionalParams['tx_news_pi1[day]'] = $dateTime->format($this->config['url']['hrDate']['day']);
            }
            if (!empty($this->config['url']['hrDate']['month'])) {
                $additionalParams['tx_news_pi1[month]'] = $dateTime->format($this->config['url']['hrDate']['month']);
            }
            if (!empty($this->config['url']['hrDate']['year'])) {
                $additionalParams['tx_news_pi1[year]'] = $dateTime->format($this->config['url']['hrDate']['year']);
            }
        }

        $additionalParamsString = http_build_query(
            $additionalParams,
            '',
            '&',
            PHP_QUERY_RFC3986
        );

        $typoLinkConfig = [
            'parameter' => $pageId,
            'additionalParams' => $additionalParamsString ? '&' . $additionalParamsString : '',
            'forceAbsoluteUrl' => 1,
        ];

        $data['loc'] = $this->cObj->typoLink_URL($typoLinkConfig);

        return $data;
    }

    /**
     * Obtains a pid for the single view from the category.
     *
     * @param int $newsId
     * @return int
     */
    protected function getSinglePidFromCategory(int $newsId): int
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
        return (int)$categoryRecord['single_pid'];
    }

    /**
     * @param array $additionalParams
     * @param array $data
     * @return array
     */
    protected function getUrlFieldParameterMap(array $additionalParams, array $data): array
    {
        if (!empty($this->config['url']['fieldToParameterMap']) &&
            \is_array($this->config['url']['fieldToParameterMap'])) {
            foreach ($this->config['url']['fieldToParameterMap'] as $field => $urlPart) {
                $additionalParams[$urlPart] = $data[$field];
            }
        }

        return $additionalParams;
    }

    /**
     * @param array $additionalParams
     * @return array
     */
    protected function getUrlAdditionalParams(array $additionalParams): array
    {
        if (!empty($this->config['url']['additionalGetParameters']) &&
            is_array($this->config['url']['additionalGetParameters'])) {
            foreach ($this->config['url']['additionalGetParameters'] as $extension => $extensionConfig) {
                foreach ($extensionConfig as $key => $value) {
                    $additionalParams[$extension . '[' . $key . ']'] = $value;
                }
            }
        }

        return $additionalParams;
    }

    /**
     * @return int
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getLanguageId(): int
    {
        $context = GeneralUtility::makeInstance(Context::class);
        return (int)$context->getPropertyFromAspect('language', 'id');
    }

    /**
     * @return WorkspaceAspect
     */
    protected function getCurrentWorkspaceAspect(): WorkspaceAspect
    {
        return GeneralUtility::makeInstance(Context::class)->getAspect('workspace');
    }
}
