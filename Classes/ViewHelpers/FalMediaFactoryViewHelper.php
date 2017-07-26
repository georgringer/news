<?php

namespace GeorgRinger\News\ViewHelpers;

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
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\MediaRenderer\FalMediaInterface;

/**
 * ViewHelper to show videos.
 */
class FalMediaFactoryViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * If the escaping interceptor should be disabled inside this ViewHelper,
     * then set this value to FALSE.
     * This is internal and NO part of the API. It is very likely to change.
     *
     * @var bool
     *
     * @internal
     */
    protected $escapingInterceptorEnabled = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Go through all given classes which implement the mediainterface
     * and use the proper ones to render the media element.
     *
     * @param string        $classes list of classes which are used to render the media object
     * @param FileReference $element Current media object
     * @param int           $width   width
     * @param int           $height  height
     *
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function render($classes, FileReference $element, $width, $height)
    {
        $content = '';
        $classList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $classes, true);

        // go through every class provided by argument
        foreach ($classList as $classData) {
            $videoObject = $this->objectManager->get($classData);

            // check interface implementation
            if (!($videoObject instanceof FalMediaInterface)) {
                throw new \UnexpectedValueException('$videoObject must implement interface \GeorgRinger\News\MediaRenderer\FalMediaInterface',
                    1385297020);
            }

            // if no content found and the implementation is enabled, try to render
            // with current implementation
            if (empty($content) && $videoObject->enabled($element)) {
                $content = $videoObject->render($element, $width, $height);
            }

            if ($content) {
                break;
            }
        }

        return $content;
    }
}
