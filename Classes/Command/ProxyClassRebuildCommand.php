<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Command;

use GeorgRinger\News\Utility\ClassCacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Rebuild proxy class cache
 */
class ProxyClassRebuildCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        GeneralUtility::makeInstance(ClassCacheManager::class)->reBuildSimple(true);
        $io = new SymfonyStyle($input, $output);
        $io->success('Rebuilt');
        return 0;
    }
}
