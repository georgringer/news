<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->setHeader(
'This file is part of the "news" Extension for TYPO3 CMS.

For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.',
true);
$config->setFinder(
    (new PhpCsFixer\Finder())
        ->ignoreVCSIgnored(true)
        ->notPath('/^.Build\//')
        ->notPath('/^Build\/php-cs-fixer\/php-cs-fixer.php/')
        ->notPath('/^Build\/phpunit\/(UnitTestsBootstrap|FunctionalTestsBootstrap).php/')
        ->notPath('/^Configuration\//')
        ->notPath('/^Documentation\//')
        ->notName('/^ext_(emconf|localconf|tables).php/')
);
return $config;
