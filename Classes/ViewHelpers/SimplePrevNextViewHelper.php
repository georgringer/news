<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers;

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper for a **simple** prev/next link.
 * Only the pid is taken into account, nothing else!
 *
 * # Example: Basic example
 * <code>
 *
 * <n:simplePrevNext pidList="{newsItem.pid}" news="{newsItem}" as="paginated" sortField="datetime">
 *    <f:if condition="{paginated}">
 *        <ul class="prev-next">
 *            <f:if condition="{paginated.prev}">
 *                <li class="previous">
 *                    <n:link newsItem="{paginated.prev}" settings="{settings}">
 *                        {paginated.prev.title}
 *                    </n:link>
 *                </li>
 *            </f:if>
 *            <f:if condition="{paginated.next}">
 *                <li class="next">
 *                    <n:link newsItem="{paginated.next}" settings="{settings}" class="next">
 *                        {paginated.next.title}
 *                    </n:link>
 *                </li>
 *            </f:if>
 *        </ul>
 *    </f:if>
 * </n:simplePrevNext>
 *
 * The attributes includeExternalType & includeInternalType allow to include internal and
 * external news types.
 *
 * </code>
 * <output>
 *  Menu with 2 li items with the link to the previous and next news item.
 * </output>
 */
class SimplePrevNextViewHelper extends AbstractViewHelper
{
    /** @var DataMapper */
    protected $dataMapper;

    /** @var bool */
    protected $escapeOutput = false;

    /**
     * Inject the DataMapper
     */
    public function injectDataMapper(DataMapper $dataMapper): void
    {
        $this->dataMapper = $dataMapper;
    }

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('news', News::class, 'news item', true);
        $this->registerArgument('pidList', 'string', 'pid list', false, '');
        $this->registerArgument('sortField', 'string', 'sort field', false, 'datetime');
        $this->registerArgument('as', 'string', 'as', true);
        $this->registerArgument('includeInternalType', 'boolean', 'Include internal news types', false, false);
        $this->registerArgument('includeExternalType', 'bool', 'Include external news types', false, false);
    }

    public function render(): string
    {
        $neighbours = $this->getNeighbours($this->arguments['news'], $this->arguments['pidList'], $this->arguments['sortField']);
        $as = $this->arguments['as'];

        $mapped = $this->mapResultToObjects($neighbours);

        $this->templateVariableContainer->add($as, $mapped);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);
        return $output;
    }

    /**
     * Map the array from DB to an understandable output
     */
    protected function mapResultToObjects(array $result): array
    {
        $out = [];
        foreach ($result as $_id => $single) {
            $out[$_id] = $this->getObject($single['uid']);
        }

        return $out;
    }

    /**
     * Get the news object from the given id
     *
     * @param int $id
     * @return mixed|null
     * @throws AspectNotFoundException
     */
    protected function getObject($id)
    {
        $record = null;

        $rawRecord = $this->getRawRecord($id);

        /** @var LanguageAspect $languageAspect */
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');

        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE']) && $languageAspect->getContentId() > 0) {
            // @extensionScannerIgnoreLine
            $overlay = $GLOBALS['TSFE']->sys_page->getLanguageOverlay(
                'tx_news_domain_model_news',
                $rawRecord,
                $languageAspect
            );
            if (!is_null($overlay)) {
                $rawRecord = $overlay;
            }
        }

        if (is_array($rawRecord)) {
            $records = $this->dataMapper->map(News::class, [$rawRecord]);
            $record = array_shift($records);
        }

        return $record;
    }

    protected function getNeighbours(News $news, string $pidList, string $sortField): array
    {
        $data = [];
        $pidList = empty($pidList) ? (string)$news->getPid() : $pidList;

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_news_domain_model_news');

        foreach (['prev', 'next'] as $label) {
            $queryBuilder = $connection->createQueryBuilder();

            $extraWhere = [
                $queryBuilder->expr()->neq('uid', $queryBuilder->createNamedParameter($news->getUid(), Connection::PARAM_INT)),
            ];
            if ((bool)($this->arguments['includeInternalType'] ?? false) === false) {
                $extraWhere[] = $queryBuilder->expr()->neq('type', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT));
            }
            if ((bool)($this->arguments['includeExternalType'] ?? false) === false) {
                $extraWhere[] = $queryBuilder->expr()->neq('type', $queryBuilder->createNamedParameter(2, Connection::PARAM_INT));
            }

            $getter = 'get' . ucfirst($sortField);
            if ($news->$getter() instanceof \DateTime) {
                if ($label === 'prev') {
                    $extraWhere[] = $queryBuilder->expr()->lt($sortField, $queryBuilder->createNamedParameter($news->$getter()->getTimestamp(), Connection::PARAM_INT));
                } else {
                    $extraWhere[] = $queryBuilder->expr()->gt($sortField, $queryBuilder->createNamedParameter($news->$getter()->getTimestamp(), Connection::PARAM_INT));
                }
            } else {
                if ($label === 'prev') {
                    $extraWhere[] = $queryBuilder->expr()->lt($sortField, $queryBuilder->createNamedParameter($news->$getter(), Connection::PARAM_STR));
                } else {
                    $extraWhere[] = $queryBuilder->expr()->gt($sortField, $queryBuilder->createNamedParameter($news->$getter(), Connection::PARAM_STR));
                }
            }

            $row = $queryBuilder
                ->select('*')
                ->from('tx_news_domain_model_news')
                ->where(
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                    $queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter(explode(',', $pidList), Connection::PARAM_INT_ARRAY))
                )
                ->andWhere(...$extraWhere)
                ->setMaxResults(1)
                ->orderBy($sortField, $label === 'prev' ? 'desc' : 'asc')
                ->executeQuery()->fetchAssociative();
            if (is_array($row)) {
                $data[$label] = $row;
            }
        }
        return $data;
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');
    }

    protected function getRawRecord(int $id): ?array
    {
        $queryBuilder = $this->getQueryBuilder();
        return $queryBuilder
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id, Connection::PARAM_INT))
            )
            ->setMaxResults(1)
            ->executeQuery()->fetchAssociative();
    }
}
