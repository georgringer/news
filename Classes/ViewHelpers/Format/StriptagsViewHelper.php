<?php

namespace GeorgRinger\News\ViewHelpers\Format;

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

/**
 * ViewHelper for the php function strip_tags
 *
 * # Example: Basic example
 * <code>
 * <n:format.striptags><p>This is a test</p></n:format.striptags>
 * </code>
 * <output>
 * This is a test
 * </output>
 *
 * # Example: Allow tags
 * <code>
 * <n:format.striptags allowTags="<a>"><p>This is a <a href="">test</a></p></n:format.striptags>
 * </code>
 * <output>
 * This is a <a href="">test</a>
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 * @see http://de.php.net/manual/de/function.strip-tags.php
 */
class StriptagsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * Strip tags
	 *
	 * @param string $allowTags Allowed tags
	 * @return string
	 */
	public function render($allowTags = '') {
		$content = strip_tags($this->renderChildren(), $allowTags);
		return $content;
	}
}
