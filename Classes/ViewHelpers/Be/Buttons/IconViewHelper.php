<?php

namespace GeorgRinger\News\ViewHelpers\Be\Buttons;

	/*                                                                        *
	 * This script belongs to the FLOW3 package "Fluid".                      *
	 *                                                                        *
	 * It is free software; you can redistribute it and/or modify it under    *
	 * the terms of the GNU Lesser General Public License as published by the *
	 * Free Software Foundation, either version 3 of the License, or (at your *
	 * option) any later version.                                             *
	 *                                                                        *
	 * This script is distributed in the hope that it will be useful, but     *
	 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
	 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
	 * General Public License for more details.                               *
	 *                                                                        *
	 * You should have received a copy of the GNU Lesser General Public       *
	 * License along with the script.                                         *
	 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
	 *                                                                        *
	 * The TYPO3 project - inspiring people to share!                         *
	 *                                                                        */

/**
 * Viewhelper which returns save button with icon
 *
 * # Example: Basic example
 * <code>
 * <f:be.buttons.icon uri="{f:uri.action()}" />
 * </code>
 * <output>
 * An icon button as known from the TYPO3 backend, skinned and linked
 * with the default action of the current controller.
 * </output>
 *
 * # Example: Basic example II
 * <code>
 * <n:be.buttons.icon uri="{f:uri.action(action:'index')}" icon="tcarecords-tx_news_domain_model_news-default"
 * title="{f:translate(key:'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:module.newsListing')}" />
 * </code>
 * <output>
 * A linked button with the icon of a news record which is linked to the index action
 * </output>
 *
 * @author Steffen Kamper <info@sk-typo3.de>
 * @author Bastian Waidelich <bastian@typo3.org>
 * @author Georg Ringer <typo3@ringerge.org>
 * @license http://www.gnu.org/copyleft/gpl.html
 */
class IconViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper {

	/**
	 * Renders an icon link as known from the TYPO3 backend
	 *
	 * @param string $icon Icon to be used
	 * @param string $uri the target URI for the link
	 * @param string $title Title attribute of the resulting link
	 * @param string $onclick onclick setting
	 * @param string $class css class
	 * @return string the rendered icon link
	 */
	public function render($icon = 'closedok', $uri = '', $title = '', $onclick = '', $class = '') {
		$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon($icon, array('title' => $title));

		if (empty($uri) && empty($onclick)) {
			$content = $icon;
		} else {
			$content = '<a onclick="' . htmlspecialchars($onclick) . '" href="' . htmlspecialchars($uri) . '" class="' . htmlspecialchars($class) . '">' . $icon . '</a>';
		}

		return $content;
	}
}
