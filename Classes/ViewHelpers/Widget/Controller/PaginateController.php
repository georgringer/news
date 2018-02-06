<?php

namespace GeorgRinger\News\ViewHelpers\Widget\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Paginate controller to create the pagination.
 * Extended version from fluid core
 *
 */
class PaginateController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController
{

    /**
     * @var array
     */
    protected $configuration = [
        'itemsPerPage' => 10,
        'insertAbove' => false,
        'insertBelow' => true,
        'maximumNumberOfLinks' => 99,
        'templatePath' => ''
    ];

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected $objects;

    /**
     * @var int
     */
    protected $currentPage = 1;

    /**
     * @var string
     */
    protected $templatePath = '';

    /**
     * @var int
     */
    protected $numberOfPages = 1;

    /**
     * @var int
     */
    protected $maximumNumberOfLinks = 99;

    /** @var int */
    protected $initialOffset = 0;
    /** @var int */
    protected $initialLimit = 0;
    /** @var int */
    protected $recordId = 0;

    /**
     * Initialize the action and get correct configuration
     *
     */
    public function initializeAction()
    {
        $this->objects = $this->widgetConfiguration['objects'];
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $this->configuration,
            (array)$this->widgetConfiguration['configuration'], true);

        $itemsPerPage = (integer)$this->configuration['itemsPerPage'];
        if ($itemsPerPage === 0) {
            throw new \RuntimeException('The itemsPerPage is 0 which is not allowed. Please also add "list.paginate.itemsPerPage" to the TS setting settings.overrideFlexformSettingsIfEmpty!',
                1400741142);
        }

        $this->numberOfPages = (int)ceil((count($this->objects) - (int)$this->initialOffset) / $itemsPerPage);
        $this->maximumNumberOfLinks = (integer)$this->configuration['maximumNumberOfLinks'];
        if (isset($this->configuration['templatePath']) && !empty($this->configuration['templatePath'])) {
            $this->templatePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->configuration['templatePath']);
        }

        if (isset($this->widgetConfiguration['initial']['offset'])) {
            $this->initialOffset = (int)$this->widgetConfiguration['initial']['offset'];
        }
        if (isset($this->widgetConfiguration['initial']['limit'])) {
            $this->initialLimit = (int)$this->widgetConfiguration['initial']['limit'];
        }
        if (isset($this->widgetConfiguration['initial']['recordId'])) {
            $this->recordId = (int)$this->widgetConfiguration['initial']['recordId'];
        }
    }

    /**
     * Main action
     *
     * @param int $currentPage
     */
    public function indexAction($currentPage = 1)
    {
        // set current page
        $this->currentPage = (integer)$currentPage;
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }

        if ($this->currentPage > $this->numberOfPages) {
            // set $modifiedObjects to NULL if the page does not exist
            $modifiedObjects = null;
        } else {
            // modify query
            $itemsPerPage = (integer)$this->configuration['itemsPerPage'];
            $query = $this->objects->getQuery();

            if ($this->currentPage === $this->numberOfPages && $this->initialLimit > 0) {
                $difference = $this->initialLimit - ((integer)($itemsPerPage * ($this->currentPage - 1)));
                if ($difference > 0) {
                    $query->setLimit($difference);
                } else {
                    $query->setLimit($itemsPerPage);
                }
            } else {
                $query->setLimit($itemsPerPage);
            }

            if ($this->currentPage > 1) {
                $offset = (integer)($itemsPerPage * ($this->currentPage - 1));
                $offset += $this->initialOffset;
                $query->setOffset($offset);
            } elseif ($this->initialOffset > 0) {
                $query->setOffset($this->initialOffset);
            }
            $modifiedObjects = $query->execute();
        }

        if ($this->currentPage > 1) {
            $pageLabel = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('paginate_overall', 'news', [$this->currentPage, $this->numberOfPages]);
            $GLOBALS['TSFE']->page['title'] = $GLOBALS['TSFE']->page['title'] . ' - ' . trim($pageLabel, '.');
        }

        $this->view->assign('contentArguments', [
            $this->widgetConfiguration['as'] => $modifiedObjects
        ]);
        $this->view->assign('configuration', $this->configuration);
        $this->view->assign('recordId', $this->recordId);
        $this->view->assign('pageId', $this->getCurrentPageId());
        $this->view->assign('pagination', $this->buildPagination());

        if (!empty($this->templatePath)) {
            $this->view->setTemplatePathAndFilename($this->templatePath);
        }
    }

    /**
     * Returns an array with the keys "pages", "current", "numberOfPages", "nextPage" & "previousPage"
     *
     * @return array
     */
    protected function buildPagination()
    {
        $this->calculateDisplayRange();
        $pages = [];
        for ($i = $this->displayRangeStart; $i <= $this->displayRangeEnd; $i++) {
            $pages[] = ['number' => $i, 'isCurrent' => $i === $this->currentPage];
        }
        $pagination = [
            'pages' => $pages,
            'current' => $this->currentPage,
            'numberOfPages' => $this->numberOfPages,
            'displayRangeStart' => $this->displayRangeStart,
            'displayRangeEnd' => $this->displayRangeEnd,
            'hasLessPages' => $this->displayRangeStart > 2,
            'hasMorePages' => $this->displayRangeEnd + 1 < $this->numberOfPages
        ];
        if ($this->currentPage < $this->numberOfPages) {
            $pagination['nextPage'] = $this->currentPage + 1;
        }
        if ($this->currentPage > 1) {
            $pagination['previousPage'] = $this->currentPage - 1;
        }
        return $pagination;
    }

    /**
     * If a certain number of links should be displayed, adjust before and after
     * amounts accordingly.
     *
     */
    protected function calculateDisplayRange()
    {
        $maximumNumberOfLinks = $this->maximumNumberOfLinks;
        if ($maximumNumberOfLinks > $this->numberOfPages) {
            $maximumNumberOfLinks = $this->numberOfPages;
        }
        $delta = floor($maximumNumberOfLinks / 2);
        $this->displayRangeStart = $this->currentPage - $delta;
        $this->displayRangeEnd = $this->currentPage + $delta - ($maximumNumberOfLinks % 2 === 0 ? 1 : 0);
        if ($this->displayRangeStart < 1) {
            $this->displayRangeEnd -= $this->displayRangeStart - 1;
        }
        if ($this->displayRangeEnd > $this->numberOfPages) {
            $this->displayRangeStart -= $this->displayRangeEnd - $this->numberOfPages;
        }
        $this->displayRangeStart = (integer)max($this->displayRangeStart, 1);
        $this->displayRangeEnd = (integer)min($this->displayRangeEnd, $this->numberOfPages);
    }

    /**
     * @return int
     */
    protected function getCurrentPageId()
    {
        if (is_object($GLOBALS['TSFE'])) {
            return (int)$GLOBALS['TSFE']->id;
        }
        return 0;
    }
}
