<?php

namespace GeorgRinger\News\ViewHelpers\Social;

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
 * ViewHelper to add a google+ button
 * Details: http://www.google.com/webmasters/+1/button/
 *
 * Examples
 * ==============
 *
 * <n:social.googlePlus></n:social.googlePlus>
 * Result: Google Plus Button
 *
 * <n:social.googlePlus size="small"
 *        href="http://www.mydomain.tld" count="false"></n:social.googlePlus>
 * Result: Small Google Plus Button to share www.mydomain.tld
 *    without showing the counter
 *
 */
class GooglePlusViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * @var \GeorgRinger\News\Service\SettingsService
     */
    protected $pluginSettingsService;

    /**
     * @var    string
     */
    protected $tagName = 'div';

    /**
     * @var \GeorgRinger\News\Service\SettingsService $pluginSettingsService
     * @return void
     */
    public function injectSettingsService(\GeorgRinger\News\Service\SettingsService $pluginSettingsService)
    {
        $this->pluginSettingsService = $pluginSettingsService;
    }

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerTagAttribute('size', 'string', 'Size of the icon. Can be small,medium,tall.');
        $this->registerTagAttribute('callback', 'string', 'Callback function');
        $this->registerTagAttribute('href', 'string', 'URL to be +1, default is current URL');
        $this->registerTagAttribute('count', 'string', 'Set it to false to hide counter');
    }

    /**
     * Render the Google+ button
     *
     * @param string $jsCode Alternative JavaScript code which is used
     * @return string
     */
    public function render($jsCode = '')
    {
        if (empty($jsCode)) {
            $jsCode = 'https://apis.google.com/js/platform.js';
        } elseif ($jsCode != '-1') {
            $jsCode = htmlspecialchars($jsCode);
        }

        $tsSettings = $this->pluginSettingsService->getSettings();
        $locale = (!empty($tsSettings['googlePlusLocale']) && strlen($tsSettings['googlePlusLocale']) <= 5) ? '{lang:\'' . $tsSettings['googlePlusLocale'] . '\'}' : '';

        $code = '<script type="text/javascript" src="' . $jsCode . '" async="async" defer="defer">' . $locale . '</script>';

        $this->tag->setContent(' ');
        $this->tag->addAttribute('class', 'g-plusone');
        if ($this->tag->hasAttribute('href')) {
            $this->tag->addAttribute('data-ref', $this->tag->getAttribute('href'));
            $this->tag->removeAttribute('href');
        }

        $code .= $this->tag->render();
        return $code;
    }
}
