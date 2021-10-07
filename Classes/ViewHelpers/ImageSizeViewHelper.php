<?php

namespace GeorgRinger\News\ViewHelpers;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Class ImageSizeViewHelper
 */
class ImageSizeViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('property', 'string', 'either width or height', true);
        $this->registerArgument('image', 'string', 'generated image', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return int
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): int {
        $value = 0;

        $typo3VersionNumber = VersionNumberUtility::convertVersionNumberToInteger(
            VersionNumberUtility::getNumericTypo3Version()
        );

        // If TYPO3 version is previous version 11
        if ($typo3VersionNumber < 11000000) {
            $usedImage = trim($arguments['image'], '/');
        } else {
            $usedImage = trim($arguments['image']);
        }

        $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        $imagesOnPage = $assetCollector->getMedia();

        if (isset($imagesOnPage[$usedImage])) {
            switch ($arguments['property']) {
                case 'width':
                    $value = $imagesOnPage[$usedImage][0];
                    break;
                case 'height':
                    $value = $imagesOnPage[$usedImage][1];
                    break;
                case 'size':
                    $file = Environment::getPublicPath() . '/' . $usedImage;
                    if (is_file($file)) {
                        $value = @filesize($file);
                    }
            }
        }

        return $value;
    }
}
