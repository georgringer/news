<?php

namespace GeorgRinger\News\Domain\Service;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\Index\FileIndexRepository;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class AbstractImportService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const UPLOAD_PATH = 'uploads/tx_news/';

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var array
     */
    protected $postPersistQueue = [];

    /**
     * @var EmConfiguration
     */
    protected $emSettings;

    /**
     * @var Folder
     */
    protected $importFolder;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;
    /**
     * AbstractImportService constructor.
     * @param PersistenceManager $persistenceManager
     * @param EmConfiguration $emSettings
     * @param ObjectManager $objectManager
     * @param CategoryRepository $categoryRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        PersistenceManager $persistenceManager,
        ObjectManager $objectManager,
        CategoryRepository $categoryRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->emSettings = GeneralUtility::makeInstance(EmConfiguration::class);
        $this->persistenceManager = $persistenceManager;
        $this->objectManager = $objectManager;
        $this->categoryRepository = $categoryRepository;
        $this->eventDispatcher = $eventDispatcher;
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
     * @return File|ProcessedFile|null
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
     * @return Folder
     */
    protected function getImportFolder(): Folder
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
     * @return ResourceStorage
     */
    protected function getResourceStorage(): ResourceStorage
    {
        return $this->getResourceFactory()->getStorageObject($this->emSettings->getStorageUidImporter());
    }

    /**
     * @return ResourceFactory
     */
    protected function getResourceFactory(): ResourceFactory
    {
        /** @var ResourceFactory $resourceFactory */
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        return $resourceFactory;
    }
}
