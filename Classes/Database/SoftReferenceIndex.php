<?php
namespace GeorgRinger\News\Database;

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
 * Class for processing news soft reference types
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class SoftReferenceIndex {

	// Token prefix
	public $tokenID_basePrefix = '';

	/**
	 * Main function through which all processing happens
	 *
	 * @param string $table Database table name
	 * @param string $field Field name for which processing occurs
	 * @param integer $uid UID of the record
	 * @param string $content The content/value of the field
	 * @param string $spKey The softlink parser key. This is only interesting if more than one parser is grouped in the same class. That is the case with this parser.
	 * @param array $spParams Parameters of the softlink parser. Basically this is the content inside optional []-brackets after the softref keys. Parameters are exploded by ";
	 * @param string $structurePath If running from inside a FlexForm structure, this is the path of the tag.
	 * @return array|boolean Result array on positive matches, see description above. Otherwise FALSE
	 */
	public function findRef($table, $field, $uid, $content, $spKey, $spParams, $structurePath = '') {
		$this->tokenID_basePrefix = $table . ':' . $uid . ':' . $field . ':' . $structurePath . ':' . $spKey;
		switch ($spKey) {
			case 'news_externalurl':
				$retVal = $this->findRefExternalUrl($content, $spParams);
				break;
			default:
				$retVal = FALSE;
				break;
		}
		return $retVal;
	}

	/**
	 * Finding external urls in the content.
	 *
	 * @param string $content The input content to analyse
	 * @param array $spParams Parameters set for the softref parser key in TCA/columns
	 * @return array $spParams Result array on positive matches, see description above. Otherwise FALSE
	 */
	private function findRefExternalUrl($content, $spParams) {
			// First, split the input string by a comma if the "linkList" parameter is set.
			// An example: the link field for images in content elements of type "textpic" or "image". This field CAN be configured to define a link per image, separated by comma.
		if (is_array($spParams) && in_array('linkList', $spParams)) {
				// Preserving whitespace on purpose.
			$linkElement = explode(',', $content);
		} else {
				// If only one element, just set in this array to make it easy below.
			$linkElement = array($content);
		}
			// Traverse the links now:
		$elements = array();
		foreach ($linkElement as $k => $typolinkValue) {
			$typolinkProperties = array();
			list($linkUrl, $browserTarget, $cssClass) = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(' ', $typolinkValue, 1);
				//Add http as default schema for external urls if none given
			if (strpos($linkUrl, '://') === FALSE) {
				$linkUrl = 'http://' . $linkUrl;
			}
			if (strlen($browserTarget)) {
				$typolinkProperties['target'] = $browserTarget;
			}
			if (strlen($cssClass)) {
				$typolinkProperties['class'] = $cssClass;
			}
			$typolinkProperties['LINK_TYPE'] = 'url';
			$typolinkProperties['url'] = $linkUrl;
			$linkElement[$k] = $this->setTypoLinkPartsElement($typolinkProperties, $elements, $linkUrl, $k);
		}
			// Return output:
		if (count($elements)) {
			$resultArray = array(
				'content' => implode(',', $linkElement),
				'elements' => $elements
			);
			return $resultArray;
		}

		return FALSE;
	}

	/**
	 * Recompile a TypoLink value from the array of properties made with getTypoLinkParts() into an elements array
	 *
	 * @param array $typolinkProperties TypoLink properties
	 * @param array $elements Array of elements to be modified with substitution / information entries.
	 * @param string $content The content to process.
	 * @param integer $idx Index value of the found element - user to make unique but stable tokenID
	 * @return string The input content, possibly containing tokens now according to the added substitution entries in $elements
	 * @see getTypoLinkParts()
	 */
	private function setTypoLinkPartsElement($typolinkProperties, &$elements, $content, $idx) {
		$errorFound = FALSE;

			// Initialize, set basic values. In any case a link will be shown
		$tokenId = $this->makeTokenID('setTypoLinkPartsElement:' . $idx);
		$elements[$tokenId . ':' . $idx] = array();
		$elements[$tokenId . ':' . $idx]['matchString'] = $content;
			// Based on link type, maybe do more:
		switch ((string)$typolinkProperties['LINK_TYPE']) {
			case 'url':
					// Mail addresses and URLs can be substituted manually:
				$elements[$tokenId . ':' . $idx]['subst'] = array(
					'type' => 'string',
					'tokenID' => $tokenId,
					'tokenValue' => $typolinkProperties['url']
				);
					// Output content will be the token instead:
				$content = '{softref:' . $tokenId . '}';
				break;
			default:
				$elements[$tokenId . ':' . $idx]['error'] = 'Could not decide typolink mode.';
				$errorFound = TRUE;
				break;
		}

		if ($errorFound) {
			return $content;
		}

			// Finally, for all entries that was rebuild with tokens, add target and class in the end:
		if (strlen($content) && strlen($typolinkProperties['target'])) {
			$content .= ' ' . $typolinkProperties['target'];
			if (strlen($typolinkProperties['class'])) {
				$content .= ' ' . $typolinkProperties['class'];
			}
		}
			// Return rebuilt typolink value:
		return $content;
	}

	/**
	 * Make Token ID for input index.
	 *
	 * @param string $index Suffix value.
	 * @return string Token ID
	 */
	private function makeTokenID($index = '') {
		return md5($this->tokenID_basePrefix . ':' . $index);
	}
}
