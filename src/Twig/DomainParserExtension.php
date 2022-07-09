<?php

namespace EmanueleMinotto\DomainParserBundle\Twig;

use Pdp\Parser;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleTest;

class DomainParserExtension extends Twig_Extension
{
    /**
     * Pdp Parser.
     */
    private \Pdp\Parser $parser;

    /**
     * Contructor used for the parser dependency.
     *
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('parse_url', fn($url) => $this->parser
                ->parseUrl($url)
                ->toArray()),
            new Twig_SimpleFunction('parse_host', fn($host) => $this->parser
                ->parseHost($host)
                ->toArray()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new Twig_SimpleTest(
                'valid suffix',
                [$this->parser, 'isSuffixValid']
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'domain_parser_extension';
    }
}
