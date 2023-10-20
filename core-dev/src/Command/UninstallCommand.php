<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class UninstallCommand extends Command {
    /**
     * {@inheritdoc}
     */
    protected function configure() {
      $this->setName('uninstall')
          ->setDescription('Uninstall Drupal by deleting settings.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
      $filesystem = new Filesystem();
      $settings = __DIR__ . '/../../../../sites/default/settings.php';
      $files = __DIR__ . '/../../../../sites/default/files';
      $filesystem->chmod($files . '/../', 0755);
      $filesystem->chmod($settings, 0777, 0000, true);
      $filesystem->remove($settings);
      $filesystem->chmod($files, 0777, 0000, true);
      $filesystem->remove($files);
      return 0;
    }
}
