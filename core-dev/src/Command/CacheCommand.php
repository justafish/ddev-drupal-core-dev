<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheCommand extends BootCommand {
    /**
     * {@inheritdoc}
     */
    protected function configure() {
      parent::configure();
      $this->setName('cache')
          ->setDescription('Clears all caches and the container registry');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
      parent::execute($input, $output);
      drupal_flush_all_caches();
      $output->writeln('Caches cleared.');
      return 0;
    }
}
