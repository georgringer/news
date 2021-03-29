<?php

namespace GeorgRinger\News\Domain\Service;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Resource\Index\FileIndexRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;
    /**
     * AbstractImportService constructor.
     * @param PersistenceManager $persistenceManager
     * @param EmConfiguration $emSettings
     * @param ObjectManager $objectManager
     * @param CategoryRepository $categoryRepository
     * @param Dispatcher $signalSlotDispatcher
     */
    public function __construct(
        PersistenceManager $persistenceManager,
        EmConfiguration $emSettings,
        ObjectManager $objectManager,
        CategoryRepository $categoryRepository,
        Dispatcher $signalSlotDispatcher
    ) {
        $this->emSettings = $emSettings;
        $this->persistenceManager = $persistenceManager;
        $this->objectManager = $objectManager;
        $this->categoryRepository = $categoryRepository;
        $this->signalSlotDispatcher = $signalSlotDispatcher;
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
