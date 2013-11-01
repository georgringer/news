<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
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
 * Utility class for import jobs
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Utility_ImportJob {

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
