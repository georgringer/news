<?php

declare(strict_types=1);

namespace GeorgRinger\News\Updates;

/*
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

use GeorgRinger\News\Service\SlugService;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrate EXT:realurl unique alias into empty news slugs
 *
 * If a lot of similar titles are used it might be a good a idea
 * to migrate the unique aliases from realurl to be sure that the same alias is used
 *
 * Requires existence of DB table tx_realurl_uniqalias, but EXT:realurl requires not to be installed
 * Will only appear if missing slugs found between realurl and news, respecting language and expire date from realurl
 * Copies values from 'tx_realurl_uniqalias.value_alias' to 'tx_news_domain_model_news.path_segment'
 */
class RealurlAliasNewsSlugUpdater implements UpgradeWizardInterface
{
    const TABLE = 'tx_news_domain_model_news';

    /** @var SlugService */
    protected $slugService;

    /**
     * RealurlAliasNewsSlugUpdater constructor.
     * @param SlugService $slugService
     */
    public function __construct(
        SlugService $slugService
    ) {
        $this->slugService = $slugService;
    }

    public function executeUpdate(): bool
    {
        // user decided to migrate, migrate and mark wizard as done
        $queries = $this->slugService->performRealurlAliasMigration();

        return true;
    }

    public function updateNecessary(): bool
    {
        $updateNeeded = false;
        $elementCount = $this->slugService->countOfRealurlAliasMigrations();
        if ($elementCount > 0) {
            $updateNeeded = true;
        }
        return $updateNeeded;
    }

    /**
     * @return string[] All new fields and tables must exist
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return '[Optional] Migrate realurl alias to slug field "path_segment" of EXT:news records';
    }

    /**
     * Get description
     *
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Migrates EXT:realurl unique alias values into empty slug field "path_segment" of EXT:news records.';
    }

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'realurlAliasNewsSlug';
    }

    /**
     * Second step: Ask user to install the extensions
     *
     * @param string $inputPrefix input prefix, all names of form fields have to start with this. Append custom name in [ ... ]
     * @return string HTML output
     */
    public function getUserInput($inputPrefix): string
    {
        return '
            <div class="panel panel-danger">
                <div class="panel-heading">Are you really sure?</div>
                <div class="panel-body">
                    <p>
                        You can migrate EXT:realurl unique alias into news slugs,
                        to ensure that the same alias is used if similar news titles are used.
                    </p>
                    <p>
                        This wizard migrates only matching realurl alias for news entries, where path_segment is empty.
                        Requires database table "tx_realurl_uniqalias" from EXT:realurl, but EXT:realurl requires not to be installed.
                    </p>
                    <p>
                        Cause only empty news slugs will be generated within this migration,
                        you may decide to empty all news slugs before.
                    </p>
                    <p>
                        The result of this migration can still left empty slugs fields for news entries.
                        Therfore you should generate these slugs afterwards using the news slug upater wizard.
                    </p>
                    <div class="btn-group clearfix" data-toggle="buttons">
                        <label class="btn btn-default active">
                            <input type="radio" name="' . $inputPrefix . '[install]" value="0" checked="checked" /> No, don\'t migrate
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="' . $inputPrefix . '[install]" value="1" /> Yes, please migrate
                        </label>
                    </div>
                </div>
            </div>
        ';
    }
}
