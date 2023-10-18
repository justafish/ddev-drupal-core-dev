<?php

namespace DrupalCoreDev\Command;

use Drupal\Core\Database\ConnectionNotDefinedException;
use Drupal\Core\DrupalKernel;
use Drupal\Core\Site\Settings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Request;

abstract class BootCommand extends Command {
  /**
   * The class loader.
   *
   * @var object
   */
  protected $classLoader;

  /**
   * Constructs a new CacheCommand command.
   *
   * @param object $class_loader
   *   The class loader.
   */
  public function __construct($class_loader) {
    parent::__construct('server');
    $this->classLoader = $class_loader;
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);
    try {
      $this->boot();
    }
    catch (ConnectionNotDefinedException $e) {
      $io->getErrorStyle()->error("No installation found. Use the 'install' command.");
      return 1;
    }

    return 0;
  }

  /**
   * Boots up a Drupal environment.
   *
   * @return \Drupal\Core\DrupalKernelInterface
   *   The Drupal kernel.
   *
   * @throws \Exception
   *   Exception thrown if kernel does not boot.
   */
  protected function boot() {
    $kernel = new DrupalKernel('prod', $this->classLoader, FALSE);
    $kernel::bootEnvironment();
    $kernel->setSitePath($this->getSitePath());
    Settings::initialize($kernel->getAppRoot(), $kernel->getSitePath(), $this->classLoader);
    $kernel->boot();
    // Some services require a request to work. For example, CommentManager.
    // This is needed as generating the URL fires up entity load hooks.
    $request = Request::createFromGlobals();
    $kernel->getContainer()
      ->get('request_stack')
      ->push($request);
    $kernel->preHandle($request);

    return $kernel;
  }

  /**
   * Gets the site path.
   *
   * Defaults to 'sites/default'. For testing purposes this can be overridden
   * using the DRUPAL_DEV_SITE_PATH environment variable.
   *
   * @return string
   *   The site path to use.
   */
  protected function getSitePath() {
    return getenv('DRUPAL_DEV_SITE_PATH') ?: 'sites/default';
  }
}

