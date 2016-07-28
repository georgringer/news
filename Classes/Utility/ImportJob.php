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
 */
class ImportJob
{

    /**
     * @var array
     */
    protected static $registeredJobs = [];

    /**
     * Register an import job.
     *
     * @param string $className class name
     * @param string $title title
     * @param string $description description
     * @return void
     * @static
     */
    public static function register($className, $title, $description)
    {
        self::$registeredJobs[] = [
            'className' => $className,
            'title' => $title,
            'description' => $description
        ];
    }

    /**
     * Get all registered import jobs
     *
     * @static
     * @return array
     */
    public static function getRegisteredJobs()
    {
        return self::$registeredJobs;
    }
}
