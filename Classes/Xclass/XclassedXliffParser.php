<?php

declare(strict_types=1);

namespace GeorgRinger\News\Xclass;

use TYPO3\CMS\Core\Localization\Exception\FileNotFoundException;
use TYPO3\CMS\Core\Localization\Parser\XliffParser;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Xclass Xfliffparser to prioritize local language packs over translation server ones
 */
class XclassedXliffParser extends XliffParser
{

    /**
     * @inheritDoc
     */
    public function getParsedData($sourcePath, $languageKey)
    {
        if (strpos($sourcePath, '/news/Resources/Private/') === false) {
            return parent::getParsedData($sourcePath, $languageKey);
        }

        $this->sourcePath = $sourcePath;
        $this->languageKey = $languageKey;
        if ($this->languageKey !== 'default') {
            $this->sourcePath = $this->getLocalizedFileName($this->sourcePath, $this->languageKey, true);
            if (!@is_file($this->sourcePath)) {
                // Global localization is not available, try split localization file
                $this->sourcePath = $this->getLocalizedFileName($sourcePath, $languageKey);
            }
            if (!@is_file($this->sourcePath)) {
                throw new FileNotFoundException(sprintf('Localization file %s does not exist', $this->sourcePath), 1566204407);
            }
        }
        $LOCAL_LANG = [];
        $LOCAL_LANG[$languageKey] = $this->parseXmlFile();
        return $LOCAL_LANG;
    }
}
