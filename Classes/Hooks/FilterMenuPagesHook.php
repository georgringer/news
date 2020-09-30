<?php
declare(strict_types=1);

namespace GeorgRinger\News\Hooks;

use GeorgRinger\News\Seo\NewsAvailability;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\Menu\AbstractMenuContentObject;
use TYPO3\CMS\Frontend\ContentObject\Menu\AbstractMenuFilterPagesHookInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

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
     * Returns true if the page should be shown
     * @param array $data
     * @param array $banUidArray
     * @param bool $spacer
     * @param AbstractMenuContentObject $obj
     * @return bool
     */
    public function processFilter(array &$data, array $banUidArray, $spacer, AbstractMenuContentObject $obj)
    {
        if ($data['uid'] !== $this->getTypoScriptFrontendController()->id) {
            return true;
        }

        $language = (int)($data['_PAGES_OVERLAY_REQUESTEDLANGUAGE'] ?? 0);
        try {
            $availability = GeneralUtility::makeInstance(NewsAvailability::class);
            return $availability->check($language);
        } catch (\Exception $e) {
            // do nothing
        }
        return true;
    }

    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
