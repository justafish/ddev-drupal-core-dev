<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class TestCommand extends Command {
    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this->setName('test')
          ->setDescription('Run all core tests using the run-tests.sh script. You probably don\'t want to do this - instead run PHPUnit directly on selected tests e.g. ddev phpunit core/modules/sdc/tests');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        $command = "php ./core/scripts/run-tests.sh --php /usr/bin/php --color --keep-results --concurrency 4 --repeat 1 --sqlite './sites/default/files/.sqlite' --verbose --non-html --all";
        $tests = Process::fromShellCommandline($command);
        $output->writeln($command);
        $tests->setTimeout(0);
        $tests->run(function ($type, $data) use ($output) {
            $output->write($data);
        });
        if ($tests->getExitCode()) {
            $io->error($tests->getErrorOutput());
            return 1;
        }
        return 0;
    }
}
