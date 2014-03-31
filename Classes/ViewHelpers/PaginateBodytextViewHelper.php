<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
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
 * Paginate the bodytext which is very useful for longer texts or to increase
 * traffic.
 *
 * Example
 * ----------
 * <n:paginateBodytext object="{newsItem}"
 * 		as="bodytext" currentPage="{currentPage}">
 * 	<div class="articleBodyText">
 * 		<f:format.html>{bodytext}</f:format.html>
 * 	</div>
 * 	<f:if condition="{pagination.numberOfPages} > 1">
 * 		<div class="articlePagination">
 * 			<span>Seite</span>
 * 			<ul>
 * 				<f:for each="{pagination.pages}" as="page">
 * 					<f:if condition="{page.isCurrent}">
 * 						<f:then>
 * 							<li class="current">
 * 								{page.number}
 * 							</li>
 * 						</f:then>
 * 						<f:else>
 * 							<li>
 * 								<f:if condition="{page.number} == 1">
 * 									<f:then>
 * 										<f:link.action action="detail" arguments="{news: newsItem}">
 * 											{page.number}
 * 										</f:link.action>
 * 									</f:then>
 * 									<f:else>
 * 										<f:link.action action="detail" arguments="{news: newsItem, currentPage: page.number}">
 * 											{page.number}
 * 										</f:link.action>
 * 									</f:else>
 * 								</f:if>
 * 							</li>
 * 						</f:else>
 * 					</f:if>
 * 				</f:for>
 * 			</ul>
 * 		</div>
 * 	</f:if>
 * </n:paginateBodytext>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_PaginateBodytextViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render everything
	 *
	 * @param Tx_News_Domain_Model_News $object current news object
	 * @param string $as name of property which holds the text
	 * @param integer $currentPage Selected page
	 * @param string $token Token used to split the text
	 * @return string
	 */
	public function render(Tx_News_Domain_Model_News $object, $as, $currentPage, $token = '###more###') {
		$parts = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($token, $object->getBodytext(), TRUE);
		$numberOfPages = count($parts);

		if ($numberOfPages === 1) {
			$result = $parts[0];
		} else {
			$currentPage = (int)$currentPage;
			if ($currentPage < 1) {
				$currentPage = 1;
			} elseif ($currentPage > $numberOfPages) {
				$currentPage = $numberOfPages;
			}

			$tagsToOpen = array();
			$tagsToClose = array();

			for ($j = 0; $j < $currentPage; $j++) {
				$chunk = $parts[$j];

				while (($chunk = mb_strstr($chunk, '<'))) {
					$tag = $this->extractTag($chunk);
					$tagStrLen = mb_strlen($tag);

					if ($this->isOpeningTag($tag)) {
						if ($j < $currentPage - 1) {
							$tagsToOpen[] = $tag;
						}
						$tagsToClose[] = $tag;
					} elseif ($this->isClosingTag($tag)) {
						if ($j < $currentPage - 1) {
							array_pop($tagsToOpen);
						} elseif (mb_strpos($parts[$j], $chunk) === 0) {
							$parts[$j] = mb_substr($parts[$j], $tagStrLen);
							array_pop($tagsToOpen);
						}
						array_pop($tagsToClose);
					}

					$chunk = mb_substr($chunk, $tagStrLen);
				}
			}

			$result = join('', $tagsToOpen) . $parts[$currentPage - 1];

			while ($tag = array_pop($tagsToClose)) {
				$result .= $this->getClosingTagByOpeningTag($tag);
			}
		}

		$pages = array();
		for ($i = 1; $i <= $numberOfPages; $i ++) {
			$pages[] = array('number' => $i, 'isCurrent' => ($i === $currentPage));
		}

		$pagination = array(
			'pages' => $pages,
			'numberOfPages' => $numberOfPages,
			'current' => $currentPage
		);

		if ($currentPage < $numberOfPages) {
			$pagination['nextPage'] = $currentPage + 1;
		}
		if ($currentPage > 1) {
			$pagination['previousPage'] = $currentPage - 1;
		}

		$this->templateVariableContainer->add($as, $result);
		$this->templateVariableContainer->add('pagination', $pagination);

		return $this->renderChildren();
	}

	/**
	 * Extracts the first html tag for a given html string
	 *
	 * @param string $html
	 * @return string
	 */
	protected function extractTag($html) {
		$tag = '';
		$length = mb_strlen($html);
		for ($i = 0; $i < $length; $i++) {
			$char = mb_substr($html, $i, 1);
			$tag .= $char;
			if ($char === '>') {
				break;
			}
		}
		return $tag;
	}

	/**
	 * Checks whether a given tag is self closing
	 *
	 * @param string $tag
	 * @return boolean
	 */
	protected function isSelfClosingTag($tag) {
		return mb_substr($tag, -2) === '/>';
	}

	/**
	 * Checks whether a given tag is closing tag
	 *
	 * @param string $tag
	 * @return boolean
	 */
	protected function isClosingTag($tag) {
		return mb_substr($tag, 0, 2) === '</';
	}

	/**
	 * Checks whether a given Tag is a an opening tag
	 *
	 * @param string $tag
	 * @return boolean
	 */
	protected function isOpeningTag($tag) {
		return !($this->isSelfClosingTag($tag) || $this->isClosingTag($tag));
	}

	/**
	 * Gets a closing tag from a given opening tag
	 *
	 * @param string $openingTag
	 * @return string
	 */
	protected function getClosingTagByOpeningTag($openingTag) {
		if (!$tag = mb_strstr(mb_substr($openingTag, 1), ' ', TRUE)) {
			$tag = mb_strstr(mb_substr($openingTag, 1), '>', TRUE);
		}

		return '</' . $tag . '>';
	}
}
