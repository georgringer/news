<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Backend\RecordList;

use TYPO3\CMS\Backend\RecordList\DatabaseRecordList;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for the list rendering of administration module
 */
class NewsDatabaseRecordList extends DatabaseRecordList
{
    /**
     * Creates the URL to this script, including all relevant GPvars
     * Fixed GPvars are id, table, imagemode, returnUrl, search_field, search_levels and showLimit
     * The GPvars "sortField" and "sortRev" are also included UNLESS they are found in the $excludeList variable.
     *
     * @param string $alternativeId Alternative id value. Enter blank string for the current id ($this->id)
     * @param string $excludeList Comma separated list of fields NOT to include ("sortField" or "sortRev")
     */
    public function listURL($alternativeId = '', $table = '-1', $excludeList = ''): string
    {
        $urlParameters = [];
        if ((string)$alternativeId !== '') {
            $urlParameters['id'] = $alternativeId;
        } else {
            // @extensionScannerIgnoreLine
            $urlParameters['id'] = $this->id;
        }
        if (isset($this->thumbs)) {
            $urlParameters['imagemode'] = $this->thumbs;
        }
        if ($this->returnUrl) {
            $urlParameters['returnUrl'] = $this->returnUrl;
        }
        if ($this->searchString) {
            $urlParameters['search_field'] = $this->searchString;
        }
        if ($this->searchLevels) {
            $urlParameters['search_levels'] = $this->searchLevels;
        }
        if ($this->showLimit) {
            $urlParameters['showLimit'] = $this->showLimit;
        }
        if (isset($this->firstElementNumber)) {
            $urlParameters['pointer'] = $this->firstElementNumber;
        }
        if ((!$excludeList || !GeneralUtility::inList(
            $excludeList,
            'sortField'
        )) && $this->sortField
        ) {
            $urlParameters['sortField'] = $this->sortField;
        }
        if ((!$excludeList || !GeneralUtility::inList(
            $excludeList,
            'sortRev'
        )) && $this->sortRev
        ) {
            $urlParameters['sortRev'] = $this->sortRev;
        }
        if ($GLOBALS['TYPO3_REQUEST']->getParsedBody()['SET'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['SET'] ?? null) {
            $urlParameters['SET'] = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['SET'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['SET'] ?? null;
        }
        if ($GLOBALS['TYPO3_REQUEST']->getParsedBody()['show'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['show'] ?? null) {
            $urlParameters['show'] = (int)($GLOBALS['TYPO3_REQUEST']->getParsedBody()['show'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['show'] ?? null);
        }

        $demand = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_newsadministration_web_newsadministrationadministration'];
        if (isset($demand['demand']) && is_array($demand['demand'])) {
            $urlParameters['tx_newsadministration_web_newsadministrationadministration']['demand'] = $demand['demand'];
        }

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return $uriBuilder->buildUriFromRoute('web_NewsAdministrationAdministration', $urlParameters);
    }
}
