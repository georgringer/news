<?php

namespace GeorgRinger\News\Controller;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Exception;
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use GeorgRinger\News\Jobs\ImportJobInterface;
use GeorgRinger\News\Utility\ImportJob;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\Exception\FolderDoesNotExistException;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use UnexpectedValueException;

/**
 * Controller to import news records
 */
class ImportController extends ActionController
{

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Retrieve all available import jobs by traversing trough registered
     * import jobs and checking "isEnabled".
     *
     * @return array
     */
    protected function getAvailableJobs(): array
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
    public function indexAction(): void
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/News/Import');

        $this->view->assignMultiple(
            [
                'error' => $this->checkCorrectConfiguration(),
                'availableJobs' => array_merge([0 => ''], $this->getAvailableJobs()),
                'moduleUrl' => $uriBuilder->buildUriFromRoute($this->request->getPluginName())
            ]
        );
    }

    /**
     * Check for correct configuration
     *
     * @return string
     * @throws Exception
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
     */
    protected function checkCorrectConfiguration(): string
    {
        $error = '';

        $settings = GeneralUtility::makeInstance(EmConfiguration::class);

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
    public function runJobAction($jobClassName, $offset = 0): string
    {
        /** @var ImportJobInterface $job */
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
    public function jobInfoAction($jobClassName): string
    {
        $response = null;
        try {
            /** @var ImportJobInterface $job */
            $job = $this->objectManager->get($jobClassName);
            $response = $job->getInfo();
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $response['line'] = $e->getLine();
            $response['trace'] = $e->getTrace();

            HttpUtility::setResponseCode(HttpUtility::HTTP_STATUS_400);
        }

        return json_encode($response);
    }

    /**
     * @return ResourceFactory
     */
    protected function getResourceFactory(): ResourceFactory
    {
        return GeneralUtility::makeInstance(ResourceFactory::class);
    }
}
