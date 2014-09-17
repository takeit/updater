<?php

/*
 * This file is part of Updater.
 *
 * (c) Paweł Mikołajczuk <mikolajczuk.private@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package Updater
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 */

namespace Updater\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;

class GeneratePackageCommand extends Command
{
    private $target =  '/../../../packages/';

    private $scriptsDir =  '/../../../bin/';

    /**
     * Configure console command
     */
    public function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generates update package')
            ->setDefinition(array(
                new InputArgument('reference', InputArgument::REQUIRED, 'COMMIT or TAG'),
                new InputArgument('source', InputArgument::OPTIONAL, 'the source directory, defaults to current directory'),
                new InputArgument('target', InputArgument::OPTIONAL, 'the target directory, defaults to \'packages/\'')
            ))
            ->setHelp(
<<<EOT
This command allows you to create an update package in the target directory from given source
based on the differences in a git repository between the current state and a
specific git tree-ish.

EOT
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $reference = $input->getArgument('reference');
        $targetDir = $input->getArgument('target');
        $sourceDir = $input->getArgument('source');

        if (!$targetDir) {
            $targetDir = realpath(__DIR__ . $this->target) . '/';
        }

        if (!file_exists($targetDir)) {
            throw new \Exception($targetDir . ' not found.', 1);
        }

        if (!is_writable($targetDir)) {
            throw new \Exception($targetDir . ' is not writable.', 1);
        }

        $commandLine = 'bash ' . realpath(__DIR__ . $this->scriptsDir) . '/getChanged.sh';
        if ($sourceDir) {
            $commandLine .= ' -s ' . $sourceDir;
        }

        if ($targetDir) {
            $commandLine .= ' -t ' . $targetDir;
        }

        $commandLine .= ' -c ' . $reference;

        $process = new Process($commandLine);
        $process->run(function ($type, $buffer) use ($output) {
            if (Process::ERR === $type) {
                $output->writeln('<error>'. $buffer . '</error>');
            } else {
                $output->writeln('<info>'. $buffer . '</info>');
            }
        });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return true;
    }
}