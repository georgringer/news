<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Controller\NewsController;

final class NewsControllerOverrideSettingsEvent
{
    protected array $settings;
    protected array $tsSettings;
    protected NewsController $newsController;

    public function __construct(array $settings, array $tsSettings, NewsController $newsController)
    {
        $this->settings = $settings;
        $this->tsSettings = $tsSettings;
        $this->newsController = $newsController;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getTsSettings(): array
    {
        return $this->tsSettings;
    }

    public function getNewsController(): NewsController
    {
        return $this->newsController;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

}
