<?php

namespace EmanueleMinotto\DomainParserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Updates domains list.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class UpdateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('domain-parser:update')
            ->setDescription('Updates domains list');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $container = $this->getContainer();
        $manager = $container->get('pdp.public_suffix_list_manager');

        $output->write('Updating public suffix list... ');

        $manager->refreshPublicSuffixList();

        $output->writeln('<info>done</info>');

        return self::SUCCESS;
    }
}
