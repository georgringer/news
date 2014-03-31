<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Philipp Bergsmann <p.bergsmann@opendo.at>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * Hashes an email-address and fetches the image from gravatar
 * see: http://en.gravatar.com/site/implement/images/
 *
 * * Examples
 * ==============
 *
 * <n:social.gravatar email="{newsItem.authorEmail}" size="20" class="gravatar" />
 * Result: a img-tag with the gravatar-url with 20px square-image
 *
 * @author Philipp Bergsmann <p.bergsmann@opendo.at>
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Social_GravatarViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {
	/**
	 * @var string
	 */
	protected $tagName = 'img';

	/**
	 * Gravatar request-url
	 */
	const GRAVATAR_IMAGE_REQUEST_URL = 'https://www.gravatar.com/avatar/';

	/**
	 * initializes the arguments
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerArgument('email', 'string', 'e-mail address of the user');
		$this->registerArgument('size', 'integer', 'size since the images are square');
		$this->registerArgument('alt', 'string', 'alt-text');
	}

	/**
	 * Renders the view
	 *
	 * @return string
	 */
	public function render() {
		$size = ((int) $this->arguments['size'] > 0) ? '?s=' . (int) $this->arguments['size'] : '';
		$this->tag->addAttribute('src', self::GRAVATAR_IMAGE_REQUEST_URL . md5($this->arguments['email']) . $size);
		$this->tag->addAttribute('alt', $this->arguments['alt']);
		return $this->tag->render();
	}
}
