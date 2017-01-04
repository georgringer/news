<?php

namespace GeorgRinger\News\ViewHelpers\Widget\Ajax;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Generate additional params required for the pagination
 */
class PaginateAdditionalParamsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @param int $page
     * @return array
     */
    public function render($page = 0)
    {
        if ($page === 0) {
            return [];
        }
        $params = [
            'tx_news_pi1' => [
                '@widget_0' => [
                    'currentPage' => $page
                ]
            ]
        ];

        return $params;
    }
}
