<?php

namespace DrupalCoreDev\Command;

use Drupal\user\Entity\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdminLoginCommand extends BootCommand {
    /**
     * {@inheritdoc}
     */
    protected function configure() {
      $this->setName('login')
          ->setDescription('Generates a one-time login link for User 1');

      parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
      parent::execute($input, $output);
      $output->writeln(getenv('DDEV_PRIMARY_URL') . $this->getOneTimeLoginUrl());
      return 0;
    }

    protected function getOneTimeLoginUrl() {
      $user = User::load(1);
      \Drupal::moduleHandler()->load('user');
      return user_pass_reset_url($user);
    }
}
