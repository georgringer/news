<?php
declare(strict_types=1);

use GeorgRinger\News\Backend\FormDataProvider\NewsFlexFormManipulation;
use TYPO3\CMS\Core\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->registerForAutoconfiguration(NewsFlexFormManipulation::class)->addTag('news.NewsFlexFormManipulation');

    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.NewsFlexFormManipulation'));
};
