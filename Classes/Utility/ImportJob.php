<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Utility;

/**
 * Utility class for import jobs
 */
class ImportJob
{
    /** @var array */
    protected static $registeredJobs = [];

    /**
     * Register an import job.
     *
     * @param string $className class name
     * @param string $title title
     * @param string $description description
     */
    public static function register($className, $title, $description): void
    {
        self::$registeredJobs[] = [
            'className' => $className,
            'title' => $title,
            'description' => $description,
        ];
    }

    /**
     * Get all registered import jobs
     */
    public static function getRegisteredJobs(): array
    {
        return self::$registeredJobs;
    }
}
