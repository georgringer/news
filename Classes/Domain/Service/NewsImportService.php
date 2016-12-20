<?php

namespace GeorgRinger\News\Domain\Service;

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
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * News Import Service
 *
 */
class NewsImportService extends AbstractImportService
{

    const ACTION_IMPORT_L10N_OVERLAY = 1;

    /**
     * @var \GeorgRinger\News\Domain\Repository\NewsRepository
     */
    protected $newsRepository;

    /**
     * @var \GeorgRinger\News\Domain\Repository\TtContentRepository
     */
    protected $ttContentRepository;

    /**
     * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * @var array
     */
    protected $settings = [];

    public function __construct()
    {
        $logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $this->logger = $logger;

        parent::__construct();
    }

    /**
     * Inject the news repository
     *
     * @param \GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository
     * @return void
     */
    public function injectNewsRepository(\GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Inject the category repository
     *
     * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
     * @return void
     */
    public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Inject the ttcontent repository
     *
     * @param \GeorgRinger\News\Domain\Repository\TtContentRepository $ttContentRepository
     * @return void
     */
    public function injectTtContentRepository(
        \GeorgRinger\News\Domain\Repository\TtContentRepository $ttContentRepository
    ) {
        $this->ttContentRepository = $ttContentRepository;
    }

    /**
     * Inject SignalSlotDispatcher
     *
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
     * @return void
     */
    public function injectSignalSlotDispatcher(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher)
    {
        $this->signalSlotDispatcher = $signalSlotDispatcher;
    }

    /**
     * @param array $importItem
     * @return null|\GeorgRinger\News\Domain\Model\News
     */
    protected function initializeNewsRecord(array $importItem)
    {
        $news = null;

        $this->logger->info(sprintf('Import of news from source "%s" with id "%s"', $importItem['import_source'],
            $importItem['import_id']));

        if ($importItem['import_source'] && $importItem['import_id']) {
            $news = $this->newsRepository->findOneByImportSourceAndImportId($importItem['import_source'],
                $importItem['import_id']);
        }

        if ($news === null) {
            $news = $this->objectManager->get(\GeorgRinger\News\Domain\Model\News::class);
            $this->newsRepository->add($news);
        } else {
            $this->logger->info(sprintf('News exists already with id "%s".', $news->getUid()));
            $this->newsRepository->update($news);
        }

        return $news;
    }

    /**
     * @param \GeorgRinger\News\Domain\Model\News $news
     * @param array $importItem
     * @param array $importItemOverwrite
     * @return \GeorgRinger\News\Domain\Model\News
     */
    protected function hydrateNewsRecord(
        \GeorgRinger\News\Domain\Model\News $news,
        array $importItem,
        array $importItemOverwrite
    ) {
        if (!empty($importItemOverwrite)) {
            $importItem = array_merge($importItem, $importItemOverwrite);
        }

        $news->setPid($importItem['pid']);
        $news->setHidden($importItem['hidden']);
        $news->setStarttime($importItem['starttime']);
        $news->setEndtime($importItem['endtime']);
        if (!empty($importItem['fe_group'])) {
            $news->setFeGroup((string)$importItem['fe_group']);
        }
        $news->setTstamp($importItem['tstamp']);
        $news->setCrdate($importItem['crdate']);
        $news->setSysLanguageUid($importItem['sys_language_uid']);
        $news->setSorting((int)$importItem['sorting']);

        $news->setTitle($importItem['title']);
        $news->setTeaser($importItem['teaser']);
        $news->setBodytext($importItem['bodytext']);

        $news->setType((string)$importItem['type']);
        $news->setKeywords($importItem['keywords']);
        $news->setDatetime(new \DateTime(date('Y-m-d H:i:sP', $importItem['datetime'])));
        $news->setArchive(new \DateTime(date('Y-m-d H:i:sP', $importItem['archive'])));

        $contentElementUidArray = \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(',',
            $importItem['content_elements'], true);
        foreach ($contentElementUidArray as $contentElementUid) {
            if (is_object($contentElement = $this->ttContentRepository->findByUid($contentElementUid))) {
                $news->addContentElement($contentElement);
            }
        }

        $news->setInternalurl($importItem['internalurl']);
        $news->setExternalurl($importItem['externalurl']);

        $news->setType($importItem['type']);
        $news->setKeywords($importItem['keywords']);

        $news->setAuthor($importItem['author']);
        $news->setAuthorEmail($importItem['author_email']);

        $news->setImportId($importItem['import_id']);
        $news->setImportSource($importItem['import_source']);

        if (is_array($importItem['categories'])) {
            foreach ($importItem['categories'] as $categoryUid) {
                if ($this->settings['findCategoriesByImportSource']) {
                    $category = $this->categoryRepository->findOneByImportSourceAndImportId(
                        $this->settings['findCategoriesByImportSource'], $categoryUid);
                } else {
                    $category = $this->categoryRepository->findByUid($categoryUid);
                }

                if ($category) {
                    $news->addCategory($category);
                } else {
                    $this->logger->warning(sprintf('Category with ID "%s" was not found', $categoryUid));
                }
            }
        }

        // media relation
        if (is_array($importItem['media'])) {
            foreach ($importItem['media'] as $mediaItem) {
                // get fileobject by given identifier (file UID, combined identifier or path/filename)
                try {
                    $file = $this->getResourceFactory()->retrieveFileOrFolderObject($mediaItem['image']);
                } catch (\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
                    $file = false;
                }

                // no file found skip processing of this item
                if ($file === false) {
                    continue;
                }

                // file not inside a storage then search for same file based on hash (to prevent duplicates)
                if ($file->getStorage()->getUid() === 0) {
                    $existingFile = $this->findFileByHash($file->getSha1());
                    if ($existingFile !== null) {
                        $file = $existingFile;
                    }
                }

                /** @var $media FileReference */
                if (!$media = $this->getIfFalRelationIfAlreadyExists($news->getFalMedia(), $file)) {

                    // file not inside a storage copy the one form storage 0 to the import folder
                    if ($file->getStorage()->getUid() === 0) {
                        $file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());
                    }

                    $media = $this->objectManager->get(FileReference::class);
                    $media->setFileUid($file->getUid());
                    $news->addFalMedia($media);
                }

                if ($media) {
                    $media->setTitle($mediaItem['title']);
                    $media->setAlternative($mediaItem['alt']);
                    $media->setDescription($mediaItem['caption']);
                    $media->setShowinpreview($mediaItem['showinpreview']);
                    $media->setPid($importItem['pid']);
                }
            }
        }

        // related files
        if (is_array($importItem['related_files'])) {
            foreach ($importItem['related_files'] as $fileItem) {

                // get fileObject by given identifier (file UID, combined identifier or path/filename)
                try {
                    $file = $this->getResourceFactory()->retrieveFileOrFolderObject($fileItem['file']);
                } catch (\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
                    $file = false;
                }

                // no file found skip processing of this item
                if ($file === false) {
                    continue;
                }

                // file not inside a storage then search for same file based on hash (to prevent duplicates)
                if ($file->getStorage()->getUid() === 0) {
                    $existingFile = $this->findFileByHash($file->getSha1());
                    if ($existingFile !== null) {
                        $file = $existingFile;
                    }
                }

                /** @var $relatedFile FileReference */
                if (!$relatedFile = $this->getIfFalRelationIfAlreadyExists($news->getFalRelatedFiles(), $file)) {

                    // file not inside a storage copy the one form storage 0 to the import folder
                    if ($file->getStorage()->getUid() === 0) {
                        $file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());
                    }

                    $relatedFile = $this->objectManager->get(FileReference::class);
                    $relatedFile->setFileUid($file->getUid());
                    $news->addFalRelatedFile($relatedFile);
                }

                if ($relatedFile) {
                    $relatedFile->setTitle($fileItem['title']);
                    $relatedFile->setDescription($fileItem['description']);
                    $relatedFile->setPid($importItem['pid']);
                }
            }
        }

        if (is_array($importItem['related_links'])) {
            foreach ($importItem['related_links'] as $link) {
                /** @var $relatedLink Link */
                if (($relatedLink = $this->getRelatedLinkIfAlreadyExists($news, $link['uri'])) === false) {
                    $relatedLink = $this->objectManager->get(\GeorgRinger\News\Domain\Model\Link::class);
                    $relatedLink->setUri($link['uri']);
                    $news->addRelatedLink($relatedLink);
                }
                $relatedLink->setTitle($link['title']);
                $relatedLink->setDescription($link['description']);
                $relatedLink->setPid($importItem['pid']);
            }
        }

        $arguments = ['importItem' => $importItem, 'news' => $news];
        $this->emitSignal('postHydrate', $arguments);

        return $news;
    }

    /**
     * Import
     *
     * @param array $importData
     * @param array $importItemOverwrite
     * @param array $settings
     * @return void
     */
    public function import(array $importData, array $importItemOverwrite = [], $settings = [])
    {
        $this->settings = $settings;
        $this->logger->info(sprintf('Starting import for %s news', count($importData)));

        foreach ($importData as $importItem) {
            $arguments = ['importItem' => $importItem];
            $return = $this->emitSignal('preHydrate', $arguments);
            $importItem = $return['importItem'];

            // Store language overlay in post persist queue
            if ((int)$importItem['sys_language_uid'] > 0 && (string)$importItem['l10n_parent'] !== '0') {
                $this->postPersistQueue[$importItem['import_id']] = [
                    'action' => self::ACTION_IMPORT_L10N_OVERLAY,
                    'category' => null,
                    'importItem' => $importItem
                ];
                continue;
            }

            $news = $this->initializeNewsRecord($importItem);

            $this->hydrateNewsRecord($news, $importItem, $importItemOverwrite);
        }

        $this->persistenceManager->persistAll();

        foreach ($this->postPersistQueue as $queueItem) {
            if ($queueItem['action'] == self::ACTION_IMPORT_L10N_OVERLAY) {
                $this->importL10nOverlay($queueItem, $importItemOverwrite);
            }
        }

        $this->persistenceManager->persistAll();
    }

    /**
     * @param array $queueItem
     * @param array $importItemOverwrite
     * @return void
     */
    protected function importL10nOverlay(array $queueItem, array $importItemOverwrite)
    {
        $importItem = $queueItem['importItem'];
        $parentNews = $this->newsRepository->findOneByImportSourceAndImportId(
            $importItem['import_source'],
            $importItem['l10n_parent']
        );

        if ($parentNews !== null) {
            $news = $this->initializeNewsRecord($importItem);

            $this->hydrateNewsRecord($news, $importItem, $importItemOverwrite);

            $news->setSysLanguageUid($importItem['sys_language_uid']);
            $news->setL10nParent($parentNews->getUid());
        }
    }

    /**
     * Get an existing items from the references that matches the file
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference> $items
     * @param \TYPO3\CMS\Core\Resource\File $file
     * @return bool|FileReference
     */
    protected function getIfFalRelationIfAlreadyExists(
        \TYPO3\CMS\Extbase\Persistence\ObjectStorage $items,
        \TYPO3\CMS\Core\Resource\File $file
    ) {
        $result = false;
        if ($items->count() !== 0) {
            /** @var $item FileReference */
            foreach ($items as $item) {
                // only check already persisted items
                if ($item->getFileUid() === (int)$file->getUid()
                    ||
                    ($item->getUid() &&
                        $item->getOriginalResource()->getName() === $file->getName() &&
                        $item->getOriginalResource()->getSize() === (int)$file->getSize())
                ) {
                    $result = $item;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Get an existing related link object
     *
     * @param \GeorgRinger\News\Domain\Model\News $news
     * @param string $uri
     * @return bool|Link
     */
    protected function getRelatedLinkIfAlreadyExists(\GeorgRinger\News\Domain\Model\News $news, $uri)
    {
        $result = false;
        $links = $news->getRelatedLinks();

        if (!empty($links) && $links->count() !== 0) {
            foreach ($links as $link) {
                if ($link->getUri() === $uri) {
                    $result = $link;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Emits signal
     *
     * @param string $signalName name of the signal slot
     * @param array $signalArguments arguments for the signal slot
     */
    protected function emitSignal($signalName, array $signalArguments)
    {
        return $this->signalSlotDispatcher->dispatch('GeorgRinger\\News\\Domain\\Service\\NewsImportService', $signalName,
            $signalArguments);
    }
}
