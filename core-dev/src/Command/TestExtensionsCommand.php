<?php
#ddev-generated

namespace DrupalCoreDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class TestExtensionsCommand extends Command {
    /**
     * {@inheritdoc}
     */
    protected function configure(): void {
        $this->setName('test:extensions-enable')
          ->setDescription('Allow test modules and themes to be installed');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);
        $setting = "\$settings['extension_discovery_scan_tests'] = TRUE;\n";
        $file = __DIR__ . '/../../../../sites/default/settings.php';
        $data = file_get_contents($file);
        if (str_contains($data, $setting)) {
            return 0;
        }

        $mode = null;
        if (!is_writable($file)) {
            try {
                $stat = stat($file);
                chmod($file, 0744);
            }
            catch (\Error $e) {
                $io->getErrorStyle()->error("Could not set $file to writeable");
                return 1;
            }
            $mode = $stat['mode'] & 000777;
        }


        file_put_contents($file, $setting, FILE_APPEND);
        $output->writeln('extension_discovery_scan_tests enabled in settings.php');

        if (!is_null($mode)) {
            // Reverse the array of unhardened paths because we want to change the
            // child item before the parent item.
            try {
                chmod($file, $mode);
            }
            catch (\Error $e) {
                $io->getErrorStyle()->error("Unable to reharden permissions for $file");
                return 1;
            }
        }

        return 0;
    }
}
