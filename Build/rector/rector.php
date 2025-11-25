<?php

declare(strict_types=1);
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector;
use Ssch\TYPO3Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector;
use Ssch\TYPO3Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Ssch\TYPO3Rector\TYPO311\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector;
use Ssch\TYPO3Rector\TYPO311\v0\GetClickMenuOnIconTagParametersRector;

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
    ->withPhpSets(php82: true)
    ->withPhpVersion(PhpVersion::PHP_82)
    ->withSets([
        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,
        Typo3LevelSetList::UP_TO_TYPO3_12,
    ])
    // To have a better analysis from PHPStan, we teach it here some more things
    ->withPHPStanConfigs([Typo3Option::PHPSTAN_FOR_RECTOR_PATH])
    ->withSkip([

        //  NullCoalescingOperatorRector::class,
        NullToStrictStringFuncCallArgRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        GeneralUtilityMakeInstanceToConstructorPropertyRector::class,
    ])
    ->withImportNames(true, true, false, true)
    ->withConfiguredRule(ExtEmConfRector::class, [
        ExtEmConfRector::PHP_VERSION_CONSTRAINT => '8.2.0-8.5.99',
        ExtEmConfRector::TYPO3_VERSION_CONSTRAINT => '13.4.20-14.4.99',
        ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [],
    ])
    ->withSkip([
        GetClickMenuOnIconTagParametersRector::class,
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
