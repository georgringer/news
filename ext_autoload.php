<?php
$extensionClassesPath = t3lib_extMgm::extPath('news') . 'Classes/';
require_once(t3lib_extMgm::extPath('news') . 'Classes/Cache/ClassCacheBuilder.php');

$default = array(
	'tx_news_domain_model_dto_emconfiguration' => $extensionClassesPath . 'Domain/Model/Dto/EmConfiguration.php',
	'tx_news_hooks_suggestreceiver' => $extensionClassesPath . 'Hooks/SuggestReceiver.php',
	'tx_news_hooks_suggestreceivercall' => $extensionClassesPath . 'Hooks/SuggestReceiverCall.php',
	'tx_news_utility_compatibility' => $extensionClassesPath . 'Utility/Compatibility.php',
	'tx_news_utility_importjob' => $extensionClassesPath . 'Utility/ImportJob.php',
	'tx_news_utility_emconfiguration' => $extensionClassesPath . 'Utility/EmConfiguration.php',
	'tx_news_service_cacheservice' => $extensionClassesPath . 'Service/CacheService.php',
);

$classCacheBuilder = t3lib_div::makeInstance('Tx_News_Cache_ClassCacheBuilder');
$mergedClasses = array_merge($default, $classCacheBuilder->build());
return $mergedClasses;

?>