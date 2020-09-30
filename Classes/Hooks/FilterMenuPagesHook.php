<?php
declare(strict_types=1);

namespace GeorgRinger\News\Hooks;

use GeorgRinger\News\Seo\NewsAvailability;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\Menu\AbstractMenuContentObject;
use TYPO3\CMS\Frontend\ContentObject\Menu\AbstractMenuFilterPagesHookInterface;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Filter out detail pages with news which are not available
 */
class FilterMenuPagesHook implements AbstractMenuFilterPagesHookInterface
{
    /**
     * Returns true if the page should be hidden
     * @param array $data
     * @param array $banUidArray
     * @param bool $spacer
     * @param AbstractMenuContentObject $obj
     * @return bool
     */
    public function processFilter(array &$data, array $banUidArray, $spacer, AbstractMenuContentObject $obj)
    {
        if (!isset($data['_PAGES_OVERLAY_REQUESTEDLANGUAGE'])) {
            return false;
        }
        try {
            $availability = GeneralUtility::makeInstance(NewsAvailability::class);
            return $availability->check($data['_PAGES_OVERLAY_REQUESTEDLANGUAGE']);
        } catch (\Exception $e) {
            return false;
        }
    }

}
