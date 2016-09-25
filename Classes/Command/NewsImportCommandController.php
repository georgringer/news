<?php
namespace GeorgRinger\News\Command;

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
use GeorgRinger\News\Jobs\ImportJobInterface;
use GeorgRinger\News\Utility\ImportJob;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Controller to import news records
 *
 */
class NewsImportCommandController extends CommandController
{

    /**
     * Import for EXT:news
     */
    public function runCommand()
    {
        $jobs = $this->getAvailableJobs();
        $index = $this->output->select('Which class to import from?', array_values($jobs), null, false, 10);

        $class = $this->getChosenClass($jobs, $index);
        $job = $this->objectManager->get($class);
        $job->run(0);
    }

    /**
     * Get available classes
     *
     * @param array $jobs
     * @param string $index
     * @return string
     */
    protected function getChosenClass(array $jobs, $index)
    {
        $classToBeUsed = null;
        foreach ($jobs as $class => $title) {
            if ($index === $title) {
                $classToBeUsed = $class;
                continue;
            }
        }

        if (is_null($classToBeUsed)) {
            $this->output('<error>Sorry, the class could not be found!</error>');
            $this->sendAndExit();
        }

        return $classToBeUsed;
    }

    /**
     * Retrieve all available import jobs by traversing trough registered
     * import jobs and checking "isEnabled".
     *
     * @return array
     */
    protected function getAvailableJobs()
    {
        $availableJobs = [];
        $registeredJobs = ImportJob::getRegisteredJobs();
        foreach ($registeredJobs as $registeredJob) {
            $jobInstance = $this->objectManager->get($registeredJob['className']);
            if ($jobInstance instanceof ImportJobInterface && $jobInstance->isEnabled()) {
                $availableJobs[$registeredJob['className']] = $GLOBALS['LANG']->sL($registeredJob['title']);
            }
        }

        if (empty($availableJobs)) {
            $this->outputLine('<error>No import jobs available!</error>');
            $this->sendAndExit();
        }

        return $availableJobs;
    }
}
