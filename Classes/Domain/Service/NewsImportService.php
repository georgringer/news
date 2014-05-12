<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * News Import Service
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class Tx_News_Domain_Service_NewsImportService extends Tx_News_Domain_Service_AbstractImportService {

	const ACTION_IMPORT_L10N_OVERLAY = 1;


	/**
	 * @var Tx_News_Domain_Repository_NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var Tx_News_Domain_Repository_TtContentRepository
	 */
	protected $ttContentRepository;

	/**
	 * @var Tx_News_Domain_Repository_CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Inject the news repository
	 *
	 * @param Tx_News_Domain_Repository_NewsRepository $newsRepository
	 * @return void
	 */
	public function injectNewsRepository(Tx_News_Domain_Repository_NewsRepository $newsRepository) {
		$this->newsRepository = $newsRepository;
	}

	/**
	 * Inject the category repository
	 *
	 * @param Tx_News_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}


	/**
	 * Inject the ttcontent repository
	 *
	 * @param Tx_News_Domain_Repository_TtContentRepository $ttContentRepository
	 * @return void
	 */
	public function injectTtContentRepository(Tx_News_Domain_Repository_TtContentRepository $ttContentRepository) {
		$this->ttContentRepository = $ttContentRepository;
	}

	/**
	 * @param array $importItem
	 * @return null|Tx_News_Domain_Model_News
	 */
	protected function initializeNewsRecord(array $importItem) {
		$news = NULL;

		if ($importItem['import_source'] && $importItem['import_id']) {
			$news = $this->newsRepository->findOneByImportSourceAndImportId($importItem['import_source'],
				$importItem['import_id']);
		}

		if ($news === NULL) {
			$news = $this->objectManager->get('Tx_News_Domain_Model_News');
			$this->newsRepository->add($news);
		} else {
			$this->newsRepository->update($news);
		}

		return $news;
	}

	/**
	 * @param Tx_News_Domain_Model_News $news
	 * @param array $importItem
	 * @param array $importItemOverwrite
	 * @return Tx_News_Domain_Model_News
	 */
	protected function hydrateNewsRecord(Tx_News_Domain_Model_News $news, array $importItem, array $importItemOverwrite) {

		if (!empty($importItemOverwrite)) {
			$importItem = array_merge($importItem, $importItemOverwrite);
		}

		$news->setPid($importItem['pid']);
		$news->setHidden($importItem['hidden']);
		$news->setStarttime($importItem['starttime']);
		$news->setEndtime($importItem['endtime']);
		$news->setFeGroup($importItem['fe_group']);
		$news->setTstamp($importItem['tstamp']);
		$news->setCrdate($importItem['crdate']);

		$news->setTitle($importItem['title']);
		$news->setTeaser($importItem['teaser']);
		$news->setBodytext($importItem['bodytext']);

		$news->setType($importItem['type']);
		$news->setKeywords($importItem['keywords']);
		$news->setDatetime(new DateTime(date('Y-m-d H:i:sP', $importItem['datetime'])));
		$news->setArchive(new DateTime(date('Y-m-d H:i:sP', $importItem['archive'])));

		$contentElementUidArray = \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(',', $importItem['content_elements'], TRUE);
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

		$news->setImportid($importItem['import_id']);
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
				}
			}
		}

		/** @var $basicFileFunctions \TYPO3\CMS\Core\Utility\File\BasicFileUtility */
		$basicFileFunctions = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\BasicFileUtility');

		// media relation
		if (is_array($importItem['media'])) {

			foreach ($importItem['media'] as $mediaItem) {

				// multi media
				if ($mediaItem['type'] === Tx_News_Domain_Model_Media::MEDIA_TYPE_MULTIMEDIA) {

					if (($media = $this->getMultiMediaIfAlreadyExists($news, $mediaItem['multimedia'])) === FALSE) {
						$media = $this->objectManager->get('Tx_News_Domain_Model_Media');
						$media->setMultimedia($mediaItem['multimedia']);
						$news->addMedia($media);
					}

					if (isset($mediaItem['caption'])) {
						$media->setDescription($mediaItem['caption']);
					}
					if (isset($mediaItem['copyright'])) {
						$media->setCopyright($mediaItem['copyright']);
					}
					if (isset($mediaItem['showinpreview'])) {
						$media->setShowinpreview($mediaItem['showinpreview']);
					}
					$media->setType($mediaItem['type']);
					$media->setPid($importItem['pid']);

				// Images FAL enabled
				} elseif($this->emSettings->getUseFal() > 0) {

					// get fileobject by given identifier (file UID, combined identifier or path/filename)
					try {
						$file = $this->getResourceFactory()->retrieveFileOrFolderObject($mediaItem['image']);
					} catch (\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
						$file = FALSE;
					}

					// no file found skip processing of this item
					if ($file === FALSE) {
						continue;
					}

					/** @var $media Tx_News_Domain_Model_FileReference */
					if (!$media = $this->getIfFalRelationIfAlreadyExists($news->getFalMedia(), $file)) {

						// file not inside a storage then search for existing file or copy the one form storage 0 to the import folder
						if ($file->getStorage()->getUid() === 0) {

							// search DB for same file based on hash (to prevent duplicates)
							$existingFile = $this->findFileByHash($file->getSha1());

							// no exciting file then copy file to import folder
							if ($existingFile === NULL) {
								$file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());

								// temp work around (uid is not correctly set in $file, fixed in https://review.typo3.org/#/c/26520/)
								$file = $this->getResourceFactory()->getFileObjectByStorageAndIdentifier($this->emSettings->getStorageUidImporter(), $file->getIdentifier());
							} else {
								$file = $existingFile;
							}
						}

						$media = $this->objectManager->get('Tx_News_Domain_Model_FileReference');
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
				} else {

					if (!$media = $this->getMediaIfAlreadyExists($news, $mediaItem['image'])) {

						$uniqueName = $basicFileFunctions->getUniqueName($mediaItem['image'],
							PATH_site . self::UPLOAD_PATH);

						copy(
							PATH_site . $mediaItem['image'],
							$uniqueName
						);

						$media = $this->objectManager->get('Tx_News_Domain_Model_Media');
						$news->addMedia($media);

						$media->setImage(basename($uniqueName));
					}

					$media->setTitle($mediaItem['title']);
					$media->setAlt($mediaItem['alt']);
					$media->setCaption($mediaItem['caption']);
					$media->setType($mediaItem['type']);
					$media->setShowinpreview($mediaItem['showinpreview']);
					$media->setPid($importItem['pid']);
				}
			}
		}

		// related files
		if (is_array($importItem['related_files'])) {

			// FAL enabled
			if ($this->emSettings->getUseFal() > 0) {

				foreach ($importItem['related_files'] as $fileItem) {

					// get fileObject by given identifier (file UID, combined identifier or path/filename)
					try {
						$file = $this->getResourceFactory()->retrieveFileOrFolderObject($fileItem['file']);
					} catch(\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
						$file = FALSE;
					}

					// no file found skip processing of this item
					if ($file === FALSE) {
						continue;
					}

					/** @var $relatedFile Tx_News_Domain_Model_FileReference */
					if (!$relatedFile = $this->getIfFalRelationIfAlreadyExists($news->getFalRelatedFiles(), $file)) {

						// file not inside a storage then search for existing file or copy the one form storage 0 to the import folder
						if ($file->getStorage()->getUid() === 0) {

							// search DB for same file based on hash (to prevent duplicates)
							$existingFile = $this->findFileByHash($file->getSha1());

							// no exciting file then copy file to import folder
							if ($existingFile === NULL) {
								$file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());

								// temp work around (uid is not correctly set in $file, fixed in https://review.typo3.org/#/c/26520/)
								$file = $this->getResourceFactory()->getFileObjectByStorageAndIdentifier($this->emSettings->getStorageUidImporter(), $file->getIdentifier());
							} else {
								$file = $existingFile;
							}
						}

						$relatedFile = $this->objectManager->get('Tx_News_Domain_Model_FileReference');
						$relatedFile->setFileUid($file->getUid());
						$news->addFalRelatedFile($relatedFile);
					}

					if ($relatedFile) {
						$relatedFile->setTitle($fileItem['title']);
						$relatedFile->setDescription($fileItem['description']);
						$relatedFile->setPid($importItem['pid']);
					}
				}

			} else {

				foreach ($importItem['related_files'] as $file) {
					if (!$relatedFile = $this->getRelatedFileIfAlreadyExists($news, $file['file'])) {

						$uniqueName = $basicFileFunctions->getUniqueName($file['file'],
							PATH_site . self::UPLOAD_PATH);

						copy(
							PATH_site . $file['file'],
							$uniqueName
						);

						$relatedFile = $this->objectManager->get('Tx_News_Domain_Model_File');
						$news->addRelatedFile($relatedFile);

						$relatedFile->setFile(basename($uniqueName));
					}
					$relatedFile->setTitle($file['title']);
					$relatedFile->setDescription($file['description']);
					$relatedFile->setPid($importItem['pid']);
				}
			}
		}

		if (is_array($importItem['related_links'])) {
			foreach ($importItem['related_links'] as $link) {
				/** @var $relatedLink Tx_News_Domain_Model_Link */
				if (($relatedLink = $this->getRelatedLinkIfAlreadyExists($news, $link['uri'])) === FALSE) {
					$relatedLink = $this->objectManager->get('Tx_News_Domain_Model_Link');
					$relatedLink->setUri($link['uri']);
					$news->addRelatedLink($relatedLink);
				}
				$relatedLink->setTitle($link['title']);
				$relatedLink->setDescription($link['description']);
				$relatedLink->setPid($importItem['pid']);
			}
		}
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
	public function import(array $importData, array $importItemOverwrite = array(), $settings = array()) {
		$this->settings = $settings;

		foreach ($importData as $importItem) {

				// Store language overlay in post persist queue
			if ($importItem['sys_language_uid']) {
				$this->postPersistQueue[$importItem['import_id']] = array(
					'action' => self::ACTION_IMPORT_L10N_OVERLAY,
					'category' => NULL,
					'importItem' => $importItem
				);
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
	protected function importL10nOverlay(array $queueItem, array $importItemOverwrite) {
		$importItem = $queueItem['importItem'];
		$parentNews = $this->newsRepository->findOneByImportSourceAndImportId(
			$importItem['import_source'],
			$importItem['l10n_parent']
		);

		if ($parentNews !== NULL) {
			$importItem['import_id'] .= '|L:' . $importItem['sys_language_uid'];

			$news = $this->initializeNewsRecord($importItem);

			$this->hydrateNewsRecord($news, $importItem, $importItemOverwrite);

			$news->setSysLanguageUid($importItem['sys_language_uid']);
			$news->setL10nParent($parentNews->getUid());
		}

	}

	/**
	 * Get media file if it exists
	 *
	 * @param Tx_News_Domain_Model_News $news
	 * @param string $mediaFile
	 * @return Boolean|Tx_News_Domain_Model_Media
	 */
	protected function getMediaIfAlreadyExists(Tx_News_Domain_Model_News $news, $mediaFile) {
		$result = FALSE;
		$mediaItems = $news->getMedia();

		if (isset($mediaItems) && $mediaItems->count() !== 0) {
			foreach ($mediaItems as $mediaItem) {
				if ($mediaItem->getImage() == basename($mediaFile) &&
					$this->filesAreEqual(PATH_site . $mediaFile, PATH_site . self::UPLOAD_PATH . $mediaItem->getImage())) {
					$result = $mediaItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Get multimedia object if it exists
	 *
	 * @param Tx_News_Domain_Model_News $news
	 * @param string $url
	 * @return Boolean|Tx_News_Domain_Model_Media
	 */
	protected function getMultiMediaIfAlreadyExists(Tx_News_Domain_Model_News $news, $url) {
		$result = FALSE;
		$mediaItems = $news->getMedia();

		if (isset($mediaItem) && $mediaItems->count() !== 0) {
			foreach ($mediaItems as $mediaItem) {
				if ($mediaItem->getMultimedia() === $url) {
					$result = $mediaItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Get related file if it exists
	 *
	 * @param Tx_News_Domain_Model_News $news
	 * @param string $relatedFile
	 * @return Boolean|Tx_News_Domain_Model_File
	 */
	protected function getRelatedFileIfAlreadyExists(Tx_News_Domain_Model_News $news, $relatedFile) {
		$result = FALSE;
		$relatedItems = $news->getRelatedFiles();

		if ($relatedItems->count() !== 0) {
			foreach ($relatedItems as $relatedItem) {
				if ($relatedItem->getFile() == basename($relatedFile) &&
					$this->filesAreEqual(
						PATH_site . $relatedFile,
						PATH_site . self::UPLOAD_PATH . $relatedItem->getFile()
					)) {
					$result = $relatedItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Get an existing items from the references that matches the file
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_News_Domain_Model_FileReference> $items
	 * @param \TYPO3\CMS\Core\Resource\File $file
	 * @return bool|Tx_News_Domain_Model_FileReference
	 */
	protected function getIfFalRelationIfAlreadyExists(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $items, \TYPO3\CMS\Core\Resource\File $file) {
		$result = FALSE;
		if ($items->count() !== 0) {
			/** @var $item Tx_News_Domain_Model_FileReference */
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
	 * @param Tx_News_Domain_Model_News $news
	 * @param string $uri
	 * @return bool|Tx_News_Domain_Model_Link
	 */
	protected function getRelatedLinkIfAlreadyExists(Tx_News_Domain_Model_News $news, $uri) {
		$result = FALSE;
		$links = $news->getRelatedLinks();

		if ($links->count() !== 0) {
			foreach ($links as $link) {
				if ($link->getUri() === $uri) {
					$result = $link;
					break;
				}
			}
		}
		return $result;
	}
}
