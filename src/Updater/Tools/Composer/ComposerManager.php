<?php

/*
 * This file is part of Updater.
 *
 * (c) Paweł Mikołajczuk <mikolajczuk.private@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Updater\Tools\Composer;

use Symfony\Component\Process\Process;

/**
 * Manager class to work with Composer
 *
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 */
class ComposerManager
{
    /**
     * Run composer.phar by given action
     *
     * @param string                                           $action     Composer action (e.g. install, update, remove)
     * @param string                                           $workingDir Application whoch is going to be updated working directory
     * @param Symfony\Component\Console\Output\OutputInterface $output     Command output
     *
     * @return void
     * @throws RuntimeException Throws RuntimeException when command fails to execute
     */
    public function runComposer($action, $workingDir, $output)
    {
        $process = new Process('php ' . $workingDir . '/composer.phar ' . $action . ' --optimize-autoloader');
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
    }

    /**
     * Composer self update
     *
     * @param string                                           $workingDir Application working dir
     * @param Symfony\Component\Console\Output\OutputInterface $output     Command output
     *
     * @return void
     * @throws RuntimeException Throws RuntimeException when command fails to execute
     */
    public function selfUpdate($workingDir, $output)
    {
        $process = new Process('php ' . $workingDir . '/composer.phar self-update');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $output->writeln('<info>'. $process->getOutput() . '</info>');
    }
}
