<?php

namespace GeorgRinger\News\Utility;

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
 * Utility class for import jobs
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class ImportJob {

	/**
	 * @var array
	 */
	protected static $registeredJobs = array();

	/**
	 * Register an import job.
	 *
	 * @param string $className class name
	 * @param string $title title
	 * @param string $description description
	 * @return void
	 * @static
	 */
	public static function register($className, $title, $description) {
		self::$registeredJobs[] = array(
			'className' => $className,
			'title' => $title,
			'description' => $description
		);
	}

	/**
	 * Get all registered import jobs
	 *
	 * @static
	 * @return array
	 */
	public static function getRegisteredJobs() {
		return self::$registeredJobs;
	}
}
