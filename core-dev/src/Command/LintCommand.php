<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LintCommand extends Command {
    /**
     * {@inheritdoc}
     */
    protected function configure(): void {
        $this->setName('lint')
            ->setDescription('Run lint tests.')
            ->addOption('stop-on-failure', null, InputOption::VALUE_NONE, 'Stop all test execution once a failure is found.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $return = 0;
        $stop_on_failure = $input->getOption('stop-on-failure');

        $commands = [
            new LintPhpCsCommand(),
            new LintPhpStanCommand(),
            new LintCssCommand(),
            new LintJsCommand(),
            new LintCspellCommand(),
        ];

        foreach ($commands as $command) {
            $return_command = $command->execute($input, $output);
            if (!$return_command) {
                continue;
            }
            $return = $return_command;

            if ($stop_on_failure) {
                return $return;
            }
        }

        return $return;
    }
}
