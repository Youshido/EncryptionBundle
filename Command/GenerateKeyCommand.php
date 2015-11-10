<?php

namespace Youshido\EncryptionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class GenerateKeyCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('encryption:generate-key')
            ->setDescription('Generate random secure key for your app');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you sure want to generate new key?', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $key = $this->getContainer()->get('encyption.manager')->generateKey();
        $output->writeln(sprintf('<fg=green;bg=black>Your new key: %s</>', $key));
    }
}
