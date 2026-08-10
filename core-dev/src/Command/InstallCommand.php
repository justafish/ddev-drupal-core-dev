<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Drupal\Core\Command\InstallCommand as CoreInstallCommand;
use Drupal\Core\DrupalKernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Installs Drupal, leaving the site directory writable.
 *
 * Once a site is running, Drupal hardens the site directory to 0555 and
 * settings.php to 0444. That makes the next install fail, because the
 * installer cannot copy default.settings.php into a read-only directory.
 * Seeding settings.php with skip_permissions_hardening before handing over to
 * core keeps both writable, so reinstalling never needs a chmod or sudo.
 */
class InstallCommand extends CoreInstallCommand {

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $root = (new DrupalKernel('prod', $this->classLoader, FALSE))->getAppRoot();
    $site_path = $root . '/' . $this->getSitePath();
    $settings = $site_path . '/settings.php';

    if (!is_dir($site_path)) {
      mkdir($site_path, 0775, TRUE);
    }
    elseif (!is_writable($site_path)) {
      // A site installed before this command existed left it hardened.
      chmod($site_path, 0755);
    }

    // Core skips its own copy when settings.php is already there, so this is
    // the only chance to get the setting in before the installer reads it.
    if (!file_exists($settings)) {
      copy($root . '/sites/default/default.settings.php', $settings);
      file_put_contents($settings, "\n\$settings['skip_permissions_hardening'] = TRUE;\n", FILE_APPEND);
    }

    return parent::execute($input, $output);
  }

}
