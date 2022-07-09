<?php

namespace EmanueleMinotto\DomainParserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Parses an URL or a host.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class ParseCommand extends ContainerAwareCommand
{
    /**
     * Pdp Parser.
     *
     * @var \Pdp\Parser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('domain-parser:parse')
            ->setDescription('Parses URL')
            ->addArgument('url', InputArgument::REQUIRED, 'URL to parse')
            ->addOption(
                'host-only',
                null,
                InputOption::VALUE_NONE,
                'Parses host only'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(
        InputInterface $input,
        OutputInterface $output
    ) {
        $container = $this->getContainer();
        $this->parser = $container->get('pdp.parser');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parsed = $this->parse(
            $input->getArgument('url'),
            $input->getOption('host-only')
        );
        $output->writeln(sprintf('Parsed: <info>%s</info>', $parsed));

        foreach ($parsed->toArray() as $key => $val) {
            $output->writeln(sprintf(' <comment>%s</comment>: %s', $key, $val));
        }

        return self::SUCCESS;
    }

    /**
     * Argument parsing.
     *
     * @param string $argument Url or domain
     * @param bool   $hostOnly
     *
     * @return \Pdp\Uri\Url|\Pdp\Uri\Url\Host
     */
    private function parse($argument, $hostOnly = false)
    {
        $parsed = $this->parser->parseUrl($argument);

        if ($hostOnly) {
            return $parsed->host;
        }

        return $parsed;
    }
}
