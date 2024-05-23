<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LintCspellCommand extends Command {
    /**
     * {@inheritdoc}
     */
    protected function configure(): void {
        $this->setName('lint:cspell')
            ->setDescription('Run CSpell analysis against modified files.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $command = "cd core && git diff --name-only | sed \"s_^_../_\" | yarn run spellcheck:core --no-must-find-files --file-list stdin";
        $phpcs = Process::fromShellCommandline($command);
        $output->writeln($command);
        $phpcs->setTimeout(0);
        $phpcs->run(function ($type, $data) use ($output) {
            $output->write($data);
        });
        if ($phpcs->getExitCode()) {
            return 1;
        }
        return 0;
    }
}
