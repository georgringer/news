<?php

namespace GeorgRinger\News\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

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

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('property', 'string', 'either width or height', true);
    }

    /**
     * @return int
     */
    public function render()
    {
        $value = 0;
        $tsfe = $this->getTypoScriptFrontendController();
        if (!is_null($tsfe)) {
            switch ($this->arguments['property']) {
                case 'width':
                    $value = $tsfe->lastImageInfo[0];
                    break;
                case 'height':
                    $value = $tsfe->lastImageInfo[1];
                    break;
                default:
                    throw new \RuntimeException(sprintf('The value "%s" is not supported in ImageSizeViewHelper', $this->arguments['property']));
            }
        }
        return $value;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
