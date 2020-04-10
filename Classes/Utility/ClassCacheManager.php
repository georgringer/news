<?php
namespace GeorgRinger\News\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClassCacheManager
 */
class ClassCacheManager
{
    /**
     * Cache instance
     *
     * @var PhpFrontend
     */
    protected $classCache;

    /**
     * @var array
     */
    protected $constructorLines = [];

    /**
     * @param PhpFrontend $classCache
     */
    public function __construct(PhpFrontend $classCache = null)
    {
        if ($classCache === null) {
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            if (!$cacheManager->hasCache('news')) {
                $cacheManager->setCacheConfigurations($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']);
            }
            $this->classCache = $cacheManager->getCache('news');
        } else {
            $this->classCache = $classCache;
        }
    }

    public function reBuild()
    {
        $classPath = 'Classes/';

        if (!function_exists('token_get_all')) {
            throw new \Exception(('The function token_get_all must exist. Please install the module PHP Module Tokenizer'));
        }

        if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes'])) {
            return;
        }

        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes'] as $key => $extensionsWithThisClass) {
            $this->constructorLines = [];
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
            if (count($this->constructorLines)) {
                $code .= LF . '    public function __construct()' . LF . '    {' . LF . implode(LF, $this->constructorLines) . LF . '    }' . LF;
            }
            $code = $this->closeClassDefinition($code);

            // If an extending class is found, the file is written and
            // added to the autoloader info
            if ($extendingClassFound) {
                $cacheEntryIdentifier = 'tx_news_' . strtolower(str_replace('/', '_', $key));
                try {
                    $this->classCache->set($cacheEntryIdentifier, $code);
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

        $classParser = GeneralUtility::makeInstance(ClassParser::class);
        $classParser->parse($filePath);
        $classParserInformation = $classParser->getFirstClass();

        $code = str_replace('<?php', '', $code);
        $codeInLines = explode(LF, str_replace(CR, '', $code));
        $offsetForInnerPart = 0;

        if ($baseClass) {
            $innerPart = $codeInLines;
        } else {
            $offsetForInnerPart = $classParserInformation['start'];
            if (isset($classParserInformation['eol'])) {
                $innerPart = array_slice($codeInLines, $classParserInformation['start'],
                    ($classParserInformation['eol'] - $classParserInformation['start'] - 1));
            } else {
                $innerPart = array_slice($codeInLines, $classParserInformation['start']);
            }
        }

        if (trim($innerPart[0]) === '{') {
            unset($innerPart[0]);
        }

        // unset the constructor and save it's lines
        if (isset($classParserInformation['functions']['__construct'])) {
            $constructorInfo = $classParserInformation['functions']['__construct'];
            for ($i = $constructorInfo['start'] - $offsetForInnerPart; $i < $constructorInfo['end'] - $offsetForInnerPart; $i++) {
                if (trim($innerPart[$i]) === '{') {
                    unset($innerPart[$i]);
                    continue;
                }
                $this->constructorLines[] = $innerPart[$i];
                unset($innerPart[$i]);
            }
            unset($innerPart[$constructorInfo['start'] - $offsetForInnerPart - 1]);
            unset($innerPart[$constructorInfo['end'] - $offsetForInnerPart]);
        }

        $codePart = implode(LF, $innerPart);
        $closingBracket = strrpos($codePart, '}');
        $codePart = substr($codePart, 0, $closingBracket);

        $content = $this->getPartialInfo($filePath) . $codePart;
        return $content;
    }

    /**
     * @param string $filePath
     * @return string
     */
    protected function getPartialInfo($filePath)
    {
        return LF . '/*' . str_repeat('*', 70) . LF . "\t" .
        'this is partial from: ' . LF . "\t" . str_replace(Environment::getPublicPath(), '', $filePath) . LF . str_repeat('*',
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
