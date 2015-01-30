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
class SimplePrevNextViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/** @var \TYPO3\CMS\Core\Database\DatabaseConnection */
	protected $databaseConnection;

	/* @var $dataMapper \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper */
	protected $dataMapper;

	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Inject the DataMapper
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper
	 * @return void
	 */
	public function injectDataMapper(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper) {
		$this->dataMapper = $dataMapper;
	}

	/**
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @param string $pidList this is something
	 * @param string $sortField
	 * @param string $as
	 * @throws TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @return string
	 */
	public function render(\GeorgRinger\News\Domain\Model\News $news, $pidList = '', $sortField = 'datetime', $as) {
		$neighbours = $this->getNeighbours($news, $pidList, $sortField);

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
	protected function mapResultToObjects(array $result) {
		$out = $tmp = array();
		$count = count($result);

		switch ($count) {
			case 3:
				$tmp['prev'] = $result[0];
				$tmp['next'] = $result[2];
				break;
			case 2:
				$tmp['prev'] = $result[0];
				break;
			case 1:
				$tmp['next'] = $result[0];
				break;
			default:
				throw new \UnexpectedValueException(sprintf('Unexpected count of "%s" which is not implemented!', $count));
		}

		foreach ($tmp as $_id => $single) {
			$out[$_id] = $this->getObject($single['uid']);
		}

		return $out;
	}

	/**
	 * Get the news object from the given id
	 *
	 * @param integer $id
	 * @return mixed|null
	 */
	protected function getObject($id) {
		$record = NULL;

		$rawRecord = $this->databaseConnection->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_news', 'uid=' . (int)$id);
		if (is_array($rawRecord)) {
			$className = 'GeorgRinger\\News\\Domain\\Model\\News';

			$records = $this->dataMapper->map($className, array($rawRecord));
			$record = array_shift($records);
		}

		return $record;
	}

	/**
	 * Returns where clause for the news table
	 *
	 * @return string
	 */
	protected function getEnableFieldsWhereClauseForTable() {
		$table = 'tx_news_domain_model_news';
		if (is_object($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE']->sys_page)) {
			return $GLOBALS['TSFE']->sys_page->enableFields($table);
		} elseif (is_object($GLOBALS['BE_USER'])) {
			return \TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause($table) .
			\TYPO3\CMS\Backend\Utility\BackendUtility::BEenableFields($table) .
			\TYPO3\CMS\Core\Resource\Utility\BackendUtility::getWorkspaceWhereClause($table);
		} elseif(TYPO3_MODE === 'BE' && TYPO3_cliMode === TRUE) {
			return '';
		}

		throw new \UnexpectedValueException('No TSFE for frontend and no BE_USER for Backend defined, please report the issue!');
	}

	/**
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @param $pidList
	 * @param $sortField
	 * @return array
	 */
	protected function getNeighbours(\GeorgRinger\News\Domain\Model\News $news, $pidList, $sortField) {
		$pidList = empty($pidList) ? $news->getPid() : $pidList;

		$select = 'SELECT tx_news_domain_model_news.uid,tx_news_domain_model_news.title ';
		$from = 'FROM tx_news_domain_model_news';
		$whereClause = 'tx_news_domain_model_news.pid IN(' . $this->databaseConnection->cleanIntList($pidList) . ') '
			. $this->getEnableFieldsWhereClauseForTable();

		$query = $select . $from . '
					WHERE ' . $whereClause . ' && ' . $sortField . ' >= (SELECT MAX(' . $sortField . ')
						' . $from . '
					WHERE ' . $whereClause . ' AND ' . $sortField . ' < (SELECT ' . $sortField . '
						FROM tx_news_domain_model_news
						WHERE tx_news_domain_model_news.uid = ' . $news->getUid() . '))
					ORDER BY ' . $sortField . ' ASC
					LIMIT 3';

		$query2 = $select . $from . '
			WHERE ' . $whereClause . ' AND ' . $sortField . '= (SELECT MIN(' . $sortField . ')
				FROM tx_news_domain_model_news
				WHERE ' . $whereClause . ' AND ' . $sortField . ' >
					(SELECT ' . $sortField . '
					FROM tx_news_domain_model_news
					WHERE tx_news_domain_model_news.uid = ' . $news->getUid() . '))
			';

		$res = $this->databaseConnection->sql_query($query);
		$out = array();
		while ($row = $this->databaseConnection->sql_fetch_assoc($res)) {
			$out[] = $row;
		}
		$this->databaseConnection->sql_free_result($res);

		if (count($out) === 0) {
			$res = $this->databaseConnection->sql_query($query2);
			while ($row = $this->databaseConnection->sql_fetch_assoc($res)) {
				$out[] = $row;
			}
			$this->databaseConnection->sql_free_result($res);
			return $out;
		}
		return $out;
	}
}