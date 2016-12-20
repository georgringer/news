<?php
namespace GeorgRinger\News\Controller;

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
use GeorgRinger\News\Utility\EmConfiguration;
use GeorgRinger\News\Utility\ImportJob;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Resource\Exception\FolderDoesNotExistException;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use UnexpectedValueException;

/**
 * Controller to import news records
 *
 */
class ImportController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

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

        return $availableJobs;
    }

    /**
     * Shows the import jobs selection .
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->assignMultiple([
                'error' => $this->checkCorrectConfiguration(),
                'availableJobs' => array_merge([0 => ''], $this->getAvailableJobs()),
                'moduleUrl' => BackendUtility::getModuleUrl($this->request->getPluginName())
            ]
        );
    }

    /**
     * Check for correct configuration
     *
     * @return string
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
     */
    protected function checkCorrectConfiguration()
    {
        $error = '';
        $settings = EmConfiguration::getSettings();

        try {
            $storageId = (int)$settings->getStorageUidImporter();
            $path = $settings->getResourceFolderImporter();
            if ($storageId === 0) {
                throw new UnexpectedValueException('import.error.configuration.storageUidImporter');
            }
            if (empty($path)) {
                throw new UnexpectedValueException('import.error.configuration.resourceFolderImporter');
            }
            $storage = $this->getResourceFactory()->getStorageObject($storageId);
            $pathExists = $storage->hasFolder($path);
            if (!$pathExists) {
                throw new FolderDoesNotExistException('Folder does not exist', 1474827988);
            }
        } catch (FolderDoesNotExistException $e) {
            $error = 'import.error.configuration.resourceFolderImporter.notExist';
        } catch (UnexpectedValueException $e) {
            $error = $e->getMessage();
        }
        return $error;
    }

    /**
     * Runs an actual job.
     *
     * @param string $jobClassName
     * @param int $offset
     * @return string
     */
    public function runJobAction($jobClassName, $offset = 0)
    {
        $job = $this->objectManager->get($jobClassName);
        $job->run($offset);

        return 'OK';
    }

    /**
     * Retrieves the job info of a given jobClass
     *
     * @param  string $jobClassName
     * @return string
     */
    public function jobInfoAction($jobClassName)
    {
        $job = $this->objectManager->get($jobClassName);
        return json_encode($job->getInfo());
    }

    /**
     * @return ResourceFactory
     */
    protected function getResourceFactory()
    {
        return ResourceFactory::getInstance();
    }
}
