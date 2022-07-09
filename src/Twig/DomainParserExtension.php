<?php

namespace EmanueleMinotto\DomainParserBundle\Twig;

use Pdp\Parser;
use Twig\Extension;
use Twig\TwigFunction;
use Twig\TwigTest;

class DomainParserExtension extends Extension\AbstractExtension
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
            new TwigFunction('parse_url', fn($url) => $this->parser
                ->parseUrl($url)
                ->toArray()),
            new TwigFunction('parse_host', fn($host) => $this->parser
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
            new TwigTest(
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
