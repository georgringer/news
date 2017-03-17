<?php

namespace GeorgRinger\News\ViewHelpers\Format;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

 /**
  * ViewHelper to render Facebook Instant Articles conform output
  *
  * # Example: Basic example
  * <code>
  * <n:format.instantArticles>
  *    <h4>I am a headline</h4>
  *    <p class="bodytext">This is the text.</p>
  * </n:format.instantArticles>
  * </code>
  * <output>
  *     <h2>I am a headline</h2>
  *     <p>This is the text.</p>
  * </output>
  *
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
