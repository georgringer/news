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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to format a date, using strftime
 *
 * # Example: Basic example using default strftime
 * <code>
 * <n:format.date>{newsItem.dateTime}</n:format.date>
 * </code>
 * <output>
 * 2013-06-08
 * </output>
 *
 * # Example: Basic example using default strftime and a format
 * <code>
 * <n:format.date format="%B">{newsItem.dateTime}</n:format.date>
 * </code>
 * <output>
 * June
 * </output>
 *
 * # Example: Basic example using datetime
 * <code>
 * <n:format.date format="c" strftime="0">{newsItem.crdate}</n:format.date>
 * </code>
 * <output>
 * 2004-02-12T15:19:21+00:00
 * </output>
 *
 * # Example: Render current time
 * <code>
 * <n:format.date format="c" strftime="0" currentDate="1">{newsItem.crdate}</n:format.date>
 * </code>
 * <output>
 * 2013-06-12T15:19:21+00:00
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render the supplied DateTime object as a formatted date.
	 *
	 * @param mixed $date \DateTime object or a string that is accepted by DateTime constructor
	 * @param string $format Format String which is taken to format the Date/Time
	 * @param bool $currentDate if true, the current date is used
	 * @param bool $strftime if true, the strftime is used instead of date()
	 * @return string Formatted date
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function render($date = NULL, $format = '%Y-%m-%d', $currentDate = FALSE, $strftime = TRUE) {
		GeneralUtility::deprecationLog('The ViewHelper "format.date" of EXT:news is deprecated! Use the one of the core!');

		if ($currentDate) {
			if ($strftime) {
				return strftime($format, $GLOBALS['EXEC_TIME']);
			} else {
				return date($format, $GLOBALS['EXEC_TIME']);
			}
		}

		if ($date === NULL) {
			$date = $this->renderChildren();
			if ($date === NULL) {
				return '';
			}
		}
		if (!$date instanceof \DateTime) {
			try {
				$date = new \DateTime($date);
			} catch (\Exception $exception) {
				throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('"' . $date . '" could not be parsed by DateTime constructor.', 1241722579);
			}
		}

		if ($strftime) {
			$formattedDate = strftime($format, $date->format('U'));
		} else {
			$formattedDate = date($format, $date->format('U'));
		}

		return $formattedDate;
	}
}
