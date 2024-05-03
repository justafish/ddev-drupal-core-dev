<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class LintJsCommand extends Command {
    /**
     * {@inheritdoc}
     */
    protected function configure(): void {
        $this->setName('lint:js')
            ->setDescription('Run JS coding standard analysis against core.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);
        $yarn = getcwd() . '/core/.yarn';
        $node_modules = getcwd() . '/core/node_modules';

        // Check if dependencies folder exists before to start.
        if(!file_exists($yarn) || !file_exists($node_modules)) {
            $io->error('Missing Yarn dependencies. Ensure that you run yarn install before executing this command.');
            return 1;
        }

        $command = "cd core && yarn run lint:core-js-passing";
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
