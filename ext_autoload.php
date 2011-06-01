<?php
$extensionClassesPath = t3lib_extMgm::extPath('news') . 'Classes/';
return array(
	'tx_news_utility_importjob' => $extensionClassesPath . 'Utility/ImportJob.php',
	'tx_news_hooks_suggestreceiver' => $extensionClassesPath . 'Hooks/SuggestReceiver.php',
	'tx_news_hooks_suggestreceivercall' => $extensionClassesPath . 'Hooks/SuggestReceiverCall.php',
);
?>