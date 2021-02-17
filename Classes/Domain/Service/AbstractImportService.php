<?php

namespace GeorgRinger\News\Domain\Service;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Resource\Index\FileIndexRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class AbstractImportService
{
    const UPLOAD_PATH = 'uploads/tx_news/';

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var array
     */
    protected $postPersistQueue = [];

    /**
     * @var \GeorgRinger\News\Domain\Model\Dto\EmConfiguration
     */
    protected $emSettings;

    /**
     * @var \TYPO3\CMS\Core\Resource\Folder
     */
    protected $importFolder;

    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;

    /**
     * Inject the object manager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     *
     * @return void
     */
    public function injectObjectManager(ObjectManager $objectManager): void
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Inject Persistence Manager
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager
     *
     * @return void
     */
    public function injectPersistenceManager(
        PersistenceManager $persistenceManager
    ): void {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emSettings = GeneralUtility::makeInstance(EmConfiguration::class);
    }

    /**
     * Compares 2 files by using its filesize
     *
     * @param string $file1 Absolute path and filename to file1
     * @param string $file2 Absolute path and filename to file2
     * @return bool
     */
    protected function filesAreEqual($file1, $file2): bool
    {
        return filesize($file1) === filesize($file2);
    }

    /**
     * Find a existing file by its hash
     *
     * @param string $hash
     *
     * @return \TYPO3\CMS\Core\Resource\File|\TYPO3\CMS\Core\Resource\ProcessedFile|null
     */
    protected function findFileByHash($hash)
    {
        $file = null;

        $files = $this->getFileIndexRepository()->findByContentHash($hash);
        if (count($files)) {
            foreach ($files as $fileInfo) {
                if ($fileInfo['storage'] > 0) {
                    $file = $this->getResourceFactory()->getFileObjectByStorageAndIdentifier(
                        $fileInfo['storage'],
                        $fileInfo['identifier']
                    );
                    break;
                }
            }
        }
        return $file;
    }

    /**
     * Get import Folder
     *
     * TODO: catch exception when storage/folder does not exist and return readable message to the user
     *
     * @return \TYPO3\CMS\Core\Resource\Folder
     */
    protected function getImportFolder(): \TYPO3\CMS\Core\Resource\Folder
    {
        if ($this->importFolder === null) {
            $this->importFolder = $this->getResourceFactory()->getFolderObjectFromCombinedIdentifier($this->emSettings->getStorageUidImporter() . ':' . $this->emSettings->getResourceFolderImporter());
        }
        return $this->importFolder;
    }

    /**
     * Returns an instance of the FileIndexRepository
     *
     * @return FileIndexRepository
     */
    protected function getFileIndexRepository(): FileIndexRepository
    {
        return FileIndexRepository::getInstance();
    }

    /**
     * Get resource storage
     *
     * @return \TYPO3\CMS\Core\Resource\ResourceStorage
     */
    protected function getResourceStorage(): \TYPO3\CMS\Core\Resource\ResourceStorage
    {
        return $this->getResourceFactory()->getStorageObject($this->emSettings->getStorageUidImporter());
    }

    /**
     * @return \TYPO3\CMS\Core\Resource\ResourceFactory
     */
    protected function getResourceFactory(): \TYPO3\CMS\Core\Resource\ResourceFactory
    {
        return ResourceFactory::getInstance();
    }
}
