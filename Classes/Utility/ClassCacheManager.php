<?php
namespace GeorgRinger\News\Utility;

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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClassCacheManager
 */
class ClassCacheManager
{
    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend
     */
    protected $cacheInstance;

    /**
     * Constructor
     *
     * @return self
     */
    public function __construct()
    {
        $classLoader = GeneralUtility::makeInstance(ClassLoader::class);
        $this->cacheInstance = $classLoader->initializeCache();
    }

    public function reBuild()
    {
        $classPath = 'Classes/';

        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes'] as $key => $extensionsWithThisClass) {
            $extendingClassFound = false;

            $path = ExtensionManagementUtility::extPath('news') . $classPath . $key . '.php';
            if (!is_file($path)) {
                throw new \Exception('Given file "' . $path . '" does not exist');
            }
            $code = $this->parseSingleFile($path, true);

            // Get the files from all other extensions
            foreach ($extensionsWithThisClass as $extensionKey) {
                $path = ExtensionManagementUtility::extPath($extensionKey) . $classPath . $key . '.php';
                if (is_file($path)) {
                    $extendingClassFound = true;
                    $code .= $this->parseSingleFile($path, false);
                }
            }
            $code = $this->closeClassDefinition($code);

            // If an extending class is found, the file is written and
            // added to the autoloader info
            if ($extendingClassFound) {
                $cacheEntryIdentifier = 'tx_news_' . strtolower(str_replace('/', '_', $key));
                try {
                    $this->cacheInstance->set($cacheEntryIdentifier, $code);
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }
        }
    }

    /**
     * Parse a single file and does some magic
     * - Remove the <?php tags
     * - Remove the class definition (if set)
     *
     * @param string $filePath path of the file
     * @param bool $baseClass If class definition should be removed
     * @return string path of the saved file
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    protected function parseSingleFile($filePath, $baseClass = false)
    {
        if (!is_file($filePath)) {
            throw new \InvalidArgumentException(sprintf('File "%s" could not be found', $filePath));
        }
        $code = GeneralUtility::getUrl($filePath);

        if ($baseClass) {
            $closingBracket = strrpos($code, '}');
            $content = substr($code, 0, $closingBracket);
            $content = str_replace('<?php', '', $content);
            return $content;
        } else {
            $classParser = GeneralUtility::makeInstance(ClassParser::class);
            $classParser->parse($filePath);
            $classParserInformation = $classParser->getFirstClass();
            $codeInLines = explode(LF, str_replace(CR, '', $code));

            if (isset($classParserInformation['eol'])) {
                $innerPart = array_slice($codeInLines, $classParserInformation['start'],
                    ($classParserInformation['eol'] - $classParserInformation['start'] - 1));
            } else {
                $innerPart = array_slice($codeInLines, $classParserInformation['start']);
            }

            if (trim($innerPart[0]) === '{') {
                unset($innerPart[0]);
            }
            $codePart = implode(LF, $innerPart);
            $closingBracket = strrpos($codePart, '}');
            $content = $this->getPartialInfo($filePath) . substr($codePart, 0, $closingBracket);
            return $content;
        }
    }

    /**
     * @param string $filePath
     * @return string
     */
    protected function getPartialInfo($filePath)
    {
        return LF . '/*' . str_repeat('*', 70) . LF . TAB .
        'this is partial from: ' . LF . TAB . str_replace(PATH_site, '', $filePath) . LF . str_repeat('*',
            70) . '*/' . LF;
    }

    /**
     * @param string $code
     * @return string
     */
    protected function closeClassDefinition($code)
    {
        return $code . LF . '}';
    }
}
