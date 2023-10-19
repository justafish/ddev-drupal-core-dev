<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class TestBrowserCommand extends Command {
    /**
     * {@inheritdoc}
     */
    protected function configure(): void {
        $this->setName('test:browser')
          ->setDescription('Set the browser used for tests')
          ->addArgument('browser', NULL, InputArgument::REQUIRED, 'Browser name, firefox or chrome');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        $browser = $input->getArgument('browser');
        if (!in_array($browser, ['firefox', 'chrome'])) {
          $io->getErrorStyle()->error('Browser name must be firefox or chrome.');
          return 1;
        }

        $src = __DIR__ . '/../../phpunit-' . $browser . '.xml';
        $dest = __DIR__ . '/../../../../core/phpunit.xml';
        if (!copy($src, $dest)) {
          $io->getErrorStyle()->error("File $src could not be copied to $dest");
          return 1;
        }
        $phpunit_config = file_get_contents($dest);
        $phpunit_config = str_replace('DRUPAL_CORE_DDEV_URL', getenv('DDEV_PRIMARY_URL'), $phpunit_config);
        file_put_contents($dest, $phpunit_config);

        if ($browser === 'firefox') {
          $output->writeln('Browser switched to firefox');
          $output->writeln('You can watch it running in real-time at ' . getenv('DDEV_PRIMARY_URL') . ':7901');
          $output->writeln('password: secret');
        }
        else {
          $output->writeln('Browser switched to chrome');
          $output->writeln('You can watch it running in real-time at ' . getenv('DDEV_PRIMARY_URL') . ':7900');
          $output->writeln('password: secret');
        }

        return 0;
    }
}
