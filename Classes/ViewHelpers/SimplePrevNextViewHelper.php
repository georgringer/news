<?php

namespace GeorgRinger\News\ViewHelpers;

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
use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Core\Database\DatabaseConnection;

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
 * </code>
 * <output>
 *  Menu with 2 li items with the link to the previous and next news item.
 * </output>
 *
 */
class SimplePrevNextViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /** @var \TYPO3\CMS\Core\Database\DatabaseConnection */
    protected $databaseConnection;

    /* @var $dataMapper \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper */
    protected $dataMapper;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * Inject the DataMapper
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper
     * @return void
     */
    public function injectDataMapper(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper)
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
    }

    /**
     * @return string
     */
    public function render()
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
     *
     * @param array $result
     * @return array
     */
    protected function mapResultToObjects(array $result)
    {
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
     */
    protected function getObject($id)
    {
        $record = null;

        $rawRecord = $this->databaseConnection->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_news',
            'uid=' . (int)$id);

        if (is_object($GLOBALS['TSFE']) && $GLOBALS['TSFE']->sys_language_content > 0) {
            $overlay = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
                'tx_news_domain_model_news',
                $rawRecord,
                $GLOBALS['TSFE']->sys_language_content,
                $GLOBALS['TSFE']->sys_language_contentOL
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

    /**
     * Returns where clause for the news table
     *
     * @return string
     */
    protected function getEnableFieldsWhereClauseForTable()
    {
        if (is_object($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE']->sys_page)) {
            return $GLOBALS['TSFE']->sys_page->enableFields('tx_news_domain_model_news');
        }

        return '';
    }

    /**
     * @param News $news
     * @param $pidList
     * @param $sortField
     * @return array
     */
    protected function getNeighbours(News $news, $pidList, $sortField)
    {
        $data = [];
        $pidList = empty($pidList) ? $news->getPid() : $pidList;

        foreach (['prev', 'next'] as $label) {
            $whereClause = 'sys_language_uid = 0 AND pid IN(' . $this->databaseConnection->cleanIntList($pidList) . ') '
                . $this->getEnableFieldsWhereClauseForTable();
            switch ($label) {
                case 'prev':
                    $selector = '<';
                    $order = 'desc';
                    break;
                case 'next':
                    $selector = '>';
                    $order = 'asc';
            }
            $getter = 'get' . ucfirst($sortField) . '';
            $whereClause .= sprintf(' AND %s %s %s', $sortField, $selector, $news->$getter()->getTimeStamp());
            $row = $this->getDb()->exec_SELECTgetSingleRow(
                '*',
                'tx_news_domain_model_news',
                $whereClause,
                '',
                $sortField . ' ' . $order);
            if (is_array($row)) {
                $data[$label] = $row;
            }
        }
        return $data;
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDb()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
