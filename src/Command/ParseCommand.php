<?php

namespace EmanueleMinotto\DomainParserBundle\Command;

use Pdp\Domain;
use Pdp\Rules;
use Symfony\Bundle\FrameworkBundle\Command\AbstractConfigCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Parses a URL or a host.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class ParseCommand extends AbstractConfigCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;
//    private Parser $parser;
//    public function __construct(Parser $parser, string $name = null)
//    {
//        $this->parser = $parser;
//        parent::__construct($name);
//    }


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
        $this->parser = $this->container->get('pdp.parser');
//        $container = $this->getContainer();
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
            // uses magic getter
            return $parsed->host;
        }

        return $parsed;
    }
}
