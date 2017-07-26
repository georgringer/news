<?php

namespace GeorgRinger\News\Hooks;

/*
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
use GeorgRinger\News\Domain\Model\Media;
use GeorgRinger\News\Service\CategoryService;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Resource\Exception\FolderDoesNotExistException;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Userfunc to get alternative label.
 */
class Labels
{
    /**
     * Generate additional label for category records
     * including the title of the parent category.
     *
     * @param array $params
     *
     * @return void
     */
    public function getUserLabelCategory(array &$params)
    {
        $showTranslationInformation = false;

        $getVars = GeneralUtility::_GET();
        if (isset($getVars['route']) && $getVars['route'] === '/record/edit'
            && isset($getVars['edit']) && is_array($getVars['edit'])
            && (isset($getVars['edit']['tt_content']) || isset($getVars['edit']['tx_news_domain_model_news']) || isset($getVars['edit']['sys_category']))
        ) {
            $showTranslationInformation = true;
        }

        if ($showTranslationInformation && is_array($params['row'])) {
            $params['title'] = CategoryService::translateCategoryRecord($params['row']['title'], $params['row']);
        } else {
            $params['title'] = $params['row']['title'];
        }
    }

    /**
     * Render different label for media elements.
     *
     * @param array $params configuration
     *
     * @return void
     */
    public function getUserLabelMedia(array &$params)
    {
        $ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';
        $typeInfo = $additionalHtmlContent = '';

        $type = $GLOBALS['LANG']->sL($ll.'tx_news_domain_model_media.type.I.'.$params['row']['type']);

        // Add additional info based on type
        switch ((int) $params['row']['type']) {
            // Image
            case Media::MEDIA_TYPE_IMAGE:
                $typeInfo .= $this->getTitleFromFields('title,alt,caption,image', $params['row']);

                if (!empty($params['row']['image'])) {
                    $params['row']['image'] = $this->splitFileName($params['row']['image']);
                    try {
                        $additionalHtmlContent = '<br />'.BackendUtilityCore::thumbCode($params['row'],
                                'tx_news_domain_model_media', 'image', $GLOBALS['BACK_PATH'], '', null, 0, '', '',
                                false);
                    } catch (FolderDoesNotExistException $exception) {
                        $additionalHtmlContent = '<br />'.htmlspecialchars($params['row']['image']);
                    }
                }
                break;
            // Audio & Video
            case Media::MEDIA_TYPE_MULTIMEDIA:
                $typeInfo .= $this->getTitleFromFields('caption,multimedia', $params['row']);
                break;
            // HTML
            case Media::MEDIA_TYPE_HTML:
                // Don't show html value as this could get a XSS
                $typeInfo .= $params['row']['caption'];
                break;
            default:
                $typeInfo .= $params['row']['caption'];
        }

        $title = (!empty($typeInfo)) ? $type.': '.$typeInfo : $type;
        $title = htmlspecialchars($title).$additionalHtmlContent;

        // Hook to modify the label, especially useful when using custom media relations
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['mediaLabel'])) {
            $params = ['params' => $params, 'title' => $title];
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['mediaLabel'] as $reference) {
                $title = GeneralUtility::callUserFunction($reference, $params, $this);
            }
        }

        // Preview
        if ($params['row']['showinpreview']) {
            $label = htmlspecialchars($GLOBALS['LANG']->sL($ll.'tx_news_domain_model_media.show'));
            $icon = '../'.ExtensionManagementUtility::siteRelPath('news').'Resources/Public/Icons/preview.gif';
            $title .= ' <img title="'.$label.'" src="'.$icon.'" />';
        }

        // Show the [No title] if empty
        if (empty($title)) {
            $title = BackendUtilityCore::getNoRecordTitle(true);
        }

        $params['title'] = $title;
    }

    /**
     * Get news categories based on the news id.
     *
     * @param int $newsUid
     * @param int $catMm
     *
     * @return string list of categories
     */
    protected function getCategories($newsUid, $catMm)
    {
        if ($catMm == 0) {
            return '';
        }

        $catTitles = [];
        $res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
            'sys_category.title as title',
            'tx_news_domain_model_news',
            'sys_category_mm',
            'sys_category',
            ' AND tx_news_domain_model_news.uid='.(int) $newsUid
        );
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $catTitles[] = $row['title'];
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);

        return implode(', ', $catTitles);
    }

    /**
     * Get the first filled field of a record.
     *
     * @param string $fieldList comma separated list of fields
     * @param array  $record    record
     *
     * @return string 1st used field
     */
    protected function getTitleFromFields($fieldList, $record = [])
    {
        $title = '';
        $fields = GeneralUtility::trimExplode(',', $fieldList, true);

        if (!is_array($record) || empty($record)) {
            return $title;
        }

        foreach ($fields as $fieldName) {
            if (empty($title) && isset($record[$fieldName]) && !empty($record[$fieldName])) {
                $title = $record[$fieldName];
            }
        }

        $title = $this->splitFileName($title);

        return $title;
    }

    /**
     * Split the filename.
     *
     * @param string $title
     *
     * @return string
     */
    protected function splitFileName($title)
    {
        $split = explode('|', $title);
        if (count($split) === 2 && $split[0] === $split[1]) {
            $title = $split[0];
        }

        return $title;
    }
}
