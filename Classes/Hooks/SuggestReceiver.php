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
use TYPO3\CMS\Backend\Form\Wizard\SuggestWizardDefaultReceiver;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Custom suggest receiver for tags
 *
 */
class SuggestReceiver extends SuggestWizardDefaultReceiver
{

    /**
     * Queries a table for records and completely processes them
     *
     * Returns a two-dimensional array of almost finished records;
     * they only need to be put into a <li>-structure
     *
     * @param array $params
     * @param int $recursionCounter recursion counter
     * @return mixed array of rows or FALSE if nothing found
     */
    public function queryTable(&$params, $recursionCounter = 0)
    {
        $uid = (int)GeneralUtility::_GP('uid');
        $records = parent::queryTable($params, $recursionCounter);
        if ($this->checkIfTagIsNotFound($records)) {
            $text = GeneralUtility::quoteJSvalue($params['value']);

            $javaScriptCode = '
        var value=' . $text . ';
        TYPO3.jQuery.ajax({
			url : TYPO3.settings.ajaxUrls[\'news_tag\'] ,
			method: \'POST\',
			data: {item:value,newsid:\'' . $uid . '\' },
			complete:function(result) {
			    if (result.status == 200) {
                    var arr = result.responseText.split(\'-\');
                    setFormValueFromBrowseWin(arr[5], arr[2] +  \'_\' + arr[0], arr[1]);
                    TBE_EDITOR.fieldChanged(arr[3], arr[6], arr[4], arr[5]);
			    } else {
			        alert(result.responseText);
			    }
			}
		});
';

            $javaScriptCode = trim(str_replace('"', '\'', $javaScriptCode));
            $link = implode(' ', explode(LF, $javaScriptCode));

            $records['tx_news_domain_model_tag_' . strlen($text)] = [
                'text' => '<div onclick="' . $link . '">
							<span class="suggest-path">
								<a>' .
                    sprintf($GLOBALS['LANG']->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:tag_suggest'),
                        $text) .
                    '</a>
							</span></div>',
                'table' => 'tx_news_domain_model_tag',
                'class' => 'suggest-noresults',
                'icon' => $this->getDummyIcon()->render()
            ];
        }

        return $records;
    }

    /**
     * Check if current tag is found.
     *
     * @param array $tags returned tags
     * @return bool
     */
    protected function checkIfTagIsNotFound(array $tags)
    {
        if (count($tags) === 0) {
            return true;
        }

        foreach ($tags as $tag) {
            if ($tag['label'] === $this->params['value']) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return Icon
     */
    protected function getDummyIcon()
    {
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        return $iconFactory->getIcon('tx_news_domain_model_tag', Icon::SIZE_SMALL);
    }
}
