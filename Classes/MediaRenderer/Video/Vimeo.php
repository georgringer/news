<?php

namespace GeorgRinger\News\MediaRenderer\Video;

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
use GeorgRinger\News\MediaRenderer\MediaInterface;

/**
 * Implementation of Vimeo support
 *
 */
class Vimeo implements MediaInterface
{

    /**
     * Render videos from vimeo
     *
     * @param \GeorgRinger\News\Domain\Model\Media $element
     * @param int $width
     * @param int $height
     * @return string
     */
    public function render(\GeorgRinger\News\Domain\Model\Media $element, $width, $height)
    {
        $content = '';

        $url = $this->getVimeoUrl($element);

        if ($url !== null) {
            // override width & height if both are set
            if ($element->getWidth() > 0 && $element->getHeight() > 0) {
                $width = $element->getWidth();
                $height = $element->getHeight();
            }
            $content = '<iframe src="' . htmlspecialchars($url) . '" width="' . (int)$width . '" height="' . (int)$height . '" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }

        return $content;
    }

    /**
     * Check if given element includes an url to a vimeo video
     *
     * @param \GeorgRinger\News\Domain\Model\Media $element
     * @return bool
     */
    public function enabled(\GeorgRinger\News\Domain\Model\Media $element)
    {
        $result = false;
        $url = $this->getVimeoUrl($element);
        if ($url !== null) {
            $result = true;
        }
        return $result;
    }

    /**
     * Get Vimeo url
     *
     * @param \GeorgRinger\News\Domain\Model\Media $element
     * @return null|string
     */
    public function getVimeoUrl(\GeorgRinger\News\Domain\Model\Media $element)
    {
        $videoId = null;
        $vimeoUrl = null;

        if (preg_match('/vimeo.com\/([0-9]+)/', $element->getContent(), $matches)) {
            $videoId = $matches[1];
        }

        if ($videoId) {
            $vimeoUrl = '//player.vimeo.com/video/' . $videoId . '';
        }

        return $vimeoUrl;
    }

}

