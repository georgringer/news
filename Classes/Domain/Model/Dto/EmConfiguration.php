<?php
namespace GeorgRinger\News\Domain\Model\Dto;

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

/**
 * Extension Manager configuration
 *
 */
class EmConfiguration
{

    /**
     * Fill the properties properly
     *
     * @param array $configuration em configuration
     */
    public function __construct(array $configuration)
    {
        foreach ($configuration as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @var int
     */
    protected $tagPid = 0;

    /**
     * @var boolean;
     */
    protected $prependAtCopy = true;

    /**
     * @var string;
     */
    protected $categoryRestriction = '';

    /**
     * @var bool
     */
    protected $categoryBeGroupTceFormsRestriction = false;

    /**
     * @var bool
     */
    protected $contentElementRelation = false;

    /**
     * @var bool
     */
    protected $manualSorting = false;

    /**
     * @var string
     */
    protected $archiveDate = 'date';

    /**
     * @var bool
     */
    protected $dateTimeNotRequired = false;

    /**
     * @var bool
     */
    protected $showImporter = false;

    /** @var bool */
    protected $rteForTeaser = false;

    /**
     * @var bool
     */
    protected $showAdministrationModule = true;

    /** @var bool */
    protected $hidePageTreeForAdministrationModule = false;

    /**
     * @var int
     */
    protected $storageUidImporter = 1;

    /**
     * @var string
     */
    protected $resourceFolderImporter = '/news_import';

    /**
     * @return int
     */
    public function getTagPid()
    {
        return (int)$this->tagPid;
    }

    /**
     *
     * @return bool
     */
    public function getPrependAtCopy()
    {
        return (boolean)$this->prependAtCopy;
    }

    /**
     * @return string
     */
    public function getCategoryRestriction()
    {
        return $this->categoryRestriction;
    }

    /**
     * Get categoryBeGroupTceFormsRestriction
     *
     * @return bool
     */
    public function getCategoryBeGroupTceFormsRestriction()
    {
        return $this->categoryBeGroupTceFormsRestriction;
    }

    /**
     * @return bool
     */
    public function getContentElementRelation()
    {
        return (boolean)$this->contentElementRelation;
    }

    /**
     * @return bool
     */
    public function getManualSorting()
    {
        return (boolean)$this->manualSorting;
    }

    /**
     * @return string
     */
    public function getArchiveDate()
    {
        return $this->archiveDate;
    }

    /**
     * @return bool
     */
    public function getShowImporter()
    {
        return (boolean)$this->showImporter;
    }

    /**
     * @param bool $showAdministrationModule
     * @return void
     */
    public function setShowAdministrationModule($showAdministrationModule)
    {
        $this->showAdministrationModule = $showAdministrationModule;
    }

    /**
     * @return bool
     */
    public function getShowAdministrationModule()
    {
        return $this->showAdministrationModule;
    }

    /**
     * @return bool
     */
    public function getRteForTeaser()
    {
        return $this->rteForTeaser;
    }

    /**
     * @return string
     */
    public function getResourceFolderImporter()
    {
        return $this->resourceFolderImporter;
    }

    /**
     * @return int
     */
    public function getStorageUidImporter()
    {
        return $this->storageUidImporter;
    }

    /**
     * @return bool
     */
    public function getDateTimeNotRequired()
    {
        return (bool)$this->dateTimeNotRequired;
    }

    /**
     * @return bool
     */
    public function getDateTimeRequired()
    {
        return !(bool)$this->dateTimeNotRequired;
    }

    /**
     * @return bool
     */
    public function getHidePageTreeForAdministrationModule()
    {
        return (bool)$this->hidePageTreeForAdministrationModule;
    }
}
