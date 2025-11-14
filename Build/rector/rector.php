<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\CodeQuality\General\ConvertImplicitVariablesToExplicitGlobalsRector;
use Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector;
use Ssch\TYPO3Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector;
use Ssch\TYPO3Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Ssch\TYPO3Rector\TYPO311\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/../../Build',
        __DIR__ . '/../../Classes',
        __DIR__ . '/../../Configuration',
        __DIR__ . '/../../Tests',
        __DIR__ . '/../../ext_emconf.php',
        __DIR__ . '/../../ext_localconf.php',
        __DIR__ . '/../../ext_tables.php',
    ])
    //->withPhpSets(php81: true)
    ->withPhpVersion(PhpVersion::PHP_81)
    ->withSets([
        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,
        Typo3LevelSetList::UP_TO_TYPO3_12,
    ])
    // To have a better analysis from PHPStan, we teach it here some more things
    ->withPHPStanConfigs([Typo3Option::PHPSTAN_FOR_RECTOR_PATH])
    ->withRules([
        ConvertImplicitVariablesToExplicitGlobalsRector::class,
    ])
    ->withSkip([
        GeneralUtilityMakeInstanceToConstructorPropertyRector::class,
    ])
    ->withImportNames(true, true, false, true)
    ->withConfiguredRule(ExtEmConfRector::class, [
        ExtEmConfRector::PHP_VERSION_CONSTRAINT => '8.1.0-8.5.99',
        ExtEmConfRector::TYPO3_VERSION_CONSTRAINT => '12.4.39-13.9.99',
        ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [],
    ])
    ->withSkip([
        InjectMethodToConstructorInjectionRector::class => [
            __DIR__ . '/../../Classes/Controller/AdministrationController.php',
            __DIR__ . '/../../Classes/Domain/Repository/AbstractDemandedRepository.php',
            __DIR__ . '/../../Classes/Service/SettingsService.php',
            __DIR__ . '/../../Classes/ViewHelpers/',
        ],
        DateTimeAspectInsteadOfGlobalsExecTimeRector::class => [
            __DIR__ . '/../../Tests/Unit/Backend/FormDataProvider/NewsRowInitializeNewTest.php',
        ],
    ])
;
