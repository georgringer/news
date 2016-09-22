<?php

namespace GeorgRinger\News\Domain\Service;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-05-2014 12:29
 * All code (c) Beech Applications B.V. all rights reserved
 */
use GeorgRinger\News\Utility\EmConfiguration;

class AbstractImportService implements \TYPO3\CMS\Core\SingletonInterface
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
     * @return void
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Inject Persistence Manager
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager
     * @return void
     */
    public function injectPersistenceManager(
        \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager
    ) {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emSettings = EmConfiguration::getSettings();
    }

    /**
     * Compares 2 files by using its filesize
     *
     * @param string $file1 Absolute path and filename to file1
     * @param string $file2 Absolute path and filename to file2
     * @return bool
     */
    protected function filesAreEqual($file1, $file2)
    {
        return filesize($file1) === filesize($file2);
    }

    /**
     * Find a existing file by its hash
     *
     * @param string $hash
     * @return NULL|\TYPO3\CMS\Core\Resource\File
     */
    protected function findFileByHash($hash)
    {
        $file = null;

        /**
         * As of 6.2 we can use
         * $files = FileIndexRepository->findByContentHash($hash);
         * Until then a direct DB query
         */
        $files = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
            'storage,identifier',
            'sys_file',
            'sha1=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($hash, 'sys_file')
        );
        if (count($files)) {
            foreach ($files as $fileInfo) {
                if ($fileInfo['storage'] > 0) {
                    $file = $this->getResourceFactory()->getFileObjectByStorageAndIdentifier($fileInfo['storage'],
                        $fileInfo['identifier']);
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
    protected function getImportFolder()
    {
        if ($this->importFolder === null) {
            $this->importFolder = $this->getResourceFactory()->getFolderObjectFromCombinedIdentifier($this->emSettings->getStorageUidImporter() . ':' . $this->emSettings->getResourceFolderImporter());
        }
        return $this->importFolder;
    }

    /**
     * Get resource storage
     *
     * @return \TYPO3\CMS\Core\Resource\ResourceStorage
     */
    protected function getResourceStorage()
    {
        return $this->getResourceFactory()->getStorageObject($this->emSettings->getStorageUidImporter());
    }

    /**
     * @return \TYPO3\CMS\Core\Resource\ResourceFactory
     */
    protected function getResourceFactory()
    {
        return \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
    }
}
