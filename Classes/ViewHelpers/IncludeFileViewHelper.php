<?php

namespace GeorgRinger\News\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FilePathSanitizer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

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
 */
class IncludeFileViewHelper extends AbstractViewHelper implements ViewHelperInterface
{
    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument('path', 'string', 'Path to the CSS/JS file which should be included', true);
        $this->registerArgument('compress', 'bool', 'Define if file should be compressed', false, false);
        $this->registerArgument('footer', 'bool', 'Define if JS file should be loaded in the footer', false, false);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return void
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $path = $arguments['path'];
        $compress = (bool)$arguments['compress'];
        $footer = (bool)$arguments['footer'];

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        if (TYPO3_MODE === 'FE') {
            $sanitizer = GeneralUtility::makeInstance(FilePathSanitizer::class);
            try {
                $path = $sanitizer->sanitize($path);
                // JS
                if (strtolower(substr($path, -3)) === '.js') {
                    if ($footer) {
                        $pageRenderer->addJsFooterFile($path, null, $compress, false, '', true);
                    } else {
                        $pageRenderer->addJsFile($path, null, $compress, false, '', true);
                    }

                    // CSS
                } elseif (strtolower(substr($path, -4)) === '.css') {
                    $pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress, false, '', true);
                }
            } catch (\Exception $e) {
                // do nothing (todo handle properly?)
            }
        } else {
            // JS
            if (strtolower(substr($path, -3)) === '.js') {
                $pageRenderer->addJsFile($path, null, $compress, false, '', true);

            // CSS
            } elseif (strtolower(substr($path, -4)) === '.css') {
                $pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress, false, '', true);
            }
        }
    }
}
