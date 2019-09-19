<?php

namespace GeorgRinger\News\Backend;

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Generate slug for 8.x
 */
class NewsSlugHelper
{

    /**
     * Cleans a slug value so it is used directly in the path segment of a URL.
     *
     * @param string $slug
     * @return string
     */
    public function sanitize(string $slug): string
    {
        // Convert to lowercase + remove tags
        $slug = mb_strtolower($slug, 'utf-8');
        $slug = strip_tags($slug);

        // Convert some special tokens (space, "_" and "-") to the space character
        $fallbackCharacter = '-';
        $slug = preg_replace('/[ \t\x{00A0}\-+_]+/u', $fallbackCharacter, $slug);

        // Convert extended letters to ascii equivalents
        // The specCharsToASCII() converts "€" to "EUR"
        $slug = GeneralUtility::makeInstance(CharsetConverter::class)->specCharsToASCII('utf-8', $slug);

        // Get rid of all invalid characters, but allow slashes
        $slug = preg_replace('/[^\p{L}0-9\/' . preg_quote($fallbackCharacter) . ']/u', '', $slug);

        // Convert multiple fallback characters to a single one
        if ($fallbackCharacter !== '') {
            $slug = preg_replace('/' . preg_quote($fallbackCharacter) . '{2,}/', $fallbackCharacter, $slug);
        }

        // Ensure slug is lower cased after all replacement was done
        $slug = mb_strtolower($slug, 'utf-8');
        // Extract slug, thus it does not have wrapping fallback and slash characters
        $extractedSlug = $this->extract($slug);
        // Remove trailing and beginning slashes, except if the trailing slash was added, then we'll re-add it
        $appendTrailingSlash = $extractedSlug !== '' && substr($slug, -1) === '/';
        $slug = $extractedSlug . ($appendTrailingSlash ? '/' : '');
        return $slug;
    }

    /**
     * Extracts payload of slug and removes wrapping delimiters,
     * e.g. `/hello/world/` will become `hello/world`.
     *
     * @param string $slug
     * @return string
     */
    public function extract(string $slug): string
    {
        // Convert some special tokens (space, "_" and "-") to the space character
        $fallbackCharacter = '-';
        return trim($slug, $fallbackCharacter . '/');
    }
}
