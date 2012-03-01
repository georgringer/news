<?php

class Tx_News_Utility_Modelparser {

	public static function writeToCoreCache() {
		$frontendOrBackend = TYPO3_MODE === 'FE' ? 'FE' : 'BE';
		$identifier = sha1($frontendOrBackend . TYPO3_version . PATH_site . 'autoload');

		$output = $GLOBALS['typo3CacheManager']->getCache('cache_phpcode')->get($identifier);

		$registry = self::parseAll();
		$cachedFileContent = '';
		foreach ($registry as $className => $classLocation) {
			if (strpos($output, $classLocation) === FALSE) {
				$cachedFileContent .= '\'' . $className . '\' => \'' . $classLocation . '\',' . LF;
			}
		}

		if (!empty($cachedFileContent)) {
			$output = str_replace('<?php', '', $output);
			$output = str_replace(');', $cachedFileContent . ');', $output);

			$GLOBALS['typo3CacheManager']->getCache('cache_phpcode')->set(
				$identifier,
				$output,
				array('t3lib_autoloader')
			);
		}
	}

	public static function parseAll() {
		t3lib_div::requireOnce(t3lib_extMgm::extPath('news') . 'Classes/Service/ModelCacheService.php');

		$autoload = array();
		$list = array('Domain/Model/News');
		foreach ($list as $item) {
			$identifier = self::parse($item);
			$key = 'tx_news_' . strtolower($identifier);

			$autoload[$key] = PATH_site . 'typo3temp/Cache/Code/class_cache/' . $identifier . '.php';
		}

		return $autoload;
	}

	public static function parse($key) {
		$identifier = str_replace('/', '_', $key);


		$templateCompiler = t3lib_div::makeInstance('Tx_News_Service_ModelCacheService');
		$templateCompiler->setTemplateCache($GLOBALS['typo3CacheManager']->getCache('class_cache'));

		if (!$templateCompiler->has($identifier)) {
			$path = t3lib_extMgm::extPath('news') . 'Classes/' . $key . '.php';
			$code = self::parseSingleFile($path, FALSE);
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['model_cache'][$key])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['model_cache'][$key] as $file) {
					$code .= self::parseSingleFile($file);
				}
			}

			$code .= LF . '}';

			$templateCompiler->store($identifier, $code);
		}
		return $identifier;
	}

	public static function parseSingleFile($file, $removeClassDefinition = TRUE) {
		if (!is_file) {
			throw new Exception('file not found: ' . $file);
		}
		$code = file_get_contents($file);
		$code = str_replace(array('<?php', '?>'), '', $code);

		if ($removeClassDefinition) {
			$pos = strpos($code, 'class Tx_');
			$pos2 = strpos($code, LF, $pos);

			$code = substr($code, 0, $pos) . substr($code, $pos2, strlen($code));
		}
		$code = trim($code);

		$code = '/*
					this is partial from: ' . $file . LF . '*/' . LF . $code;

			// remove last }
			// @todo make that better
		$code = substr($code, 0, -1);
		return $code;
	}

}

?>