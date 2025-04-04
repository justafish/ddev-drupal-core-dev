<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Drupal\Core\Extension\ModuleInstallerInterface;

class ModuleInstallCommand extends BootCommand {
    /**
     * {@inheritdoc}
     */
    protected function configure() {
      parent::configure();
      $this->setName('module:install')
          ->setDescription('Install modules')
          ->addArgument('modules', InputArgument::IS_ARRAY, InputArgument::REQUIRED, []);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
      parent::execute($input, $output);
      $modules = $input->getArgument('modules');
      if (count($modules) > 0) {
          $module_installer = \Drupal::service('module_installer');
          assert($module_installer instanceof ModuleInstallerInterface);
          $module_installer->install($modules);
      }
      return 0;
    }
}
