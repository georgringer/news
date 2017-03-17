<?php

namespace GeorgRinger\News\ViewHelpers\Format;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class InstantArticlesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Render content
     *
     * @return string Transformed content
     */
    public function render()
    {
        $content = $this->renderChildren();

        $content = str_replace('<h3>', '<h2>', $content);
        $content = str_replace('</h3>', '</h2>', $content);
        $content = str_replace('<h4>', '<h2>', $content);
        $content = str_replace('</h4>', '</h2>', $content);
        $content = str_replace('<h5>', '<h2>', $content);
        $content = str_replace('</h5>', '</h2>', $content);
        $content = str_replace('<h6>', '<h2>', $content);
        $content = str_replace('</h6>', '</h2>', $content);
        $content = str_replace(' class="bodytext"', '', $content);

        return $content;
    }
}
