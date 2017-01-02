<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to include a css/js file
 *
 * # Example: Basic example
 * <code>
 * <n:includeFile path="{settings.cssFile}" />
 * </code>
 * <output>
 * This will include the file provided by {settings} in the header
 * </output>
 *
 */
class IncludeFileViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Include a CSS/JS file
     *
     * @param string $path Path to the CSS/JS file which should be included
     * @param bool $compress Define if file should be compressed
     */
    public function render($path, $compress = false)
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        if (TYPO3_MODE === 'FE') {
            $path = $GLOBALS['TSFE']->tmpl->getFileName($path);

            // JS
            if (strtolower(substr($path, -3)) === '.js') {
                $pageRenderer->addJsFile($path, null, $compress);

                // CSS
            } elseif (strtolower(substr($path, -4)) === '.css') {
                $pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress);
            }
        } else {
            // JS
            if (strtolower(substr($path, -3)) === '.js') {
                $pageRenderer->addJsFile($path, null, $compress);

                // CSS
            } elseif (strtolower(substr($path, -4)) === '.css') {
                $pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress);
            }
        }
    }
}
