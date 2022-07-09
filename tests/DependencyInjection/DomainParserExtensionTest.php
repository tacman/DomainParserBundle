<?php

namespace EmanueleMinotto\DomainParserBundle\DependencyInjection;

use EmanueleMinotto\DomainParserBundle\CacheWarmer\CachedDomainsListWarmer;
use EmanueleMinotto\DomainParserBundle\Twig;
use EmanueleMinotto\DomainParserBundle\Validator\Constraints;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Pdp\Parser;
use Pdp\PublicSuffixList;
use Pdp\PublicSuffixListManager;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @covers EmanueleMinotto\DomainParserBundle\DependencyInjection\DomainParserExtension
 */
class DomainParserExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions(): array
    {
        return [
            new DomainParserExtension(),
        ];
    }

    /**
     * Test parameters definition.
     *
     * @param string $parameter
     *
     * @dataProvider parametersProvider
     */
    public function testParameters($parameter)
    {
        $this->load();

        $this->assertContainerBuilderHasParameter($parameter);
    }

    /**
     * @return array
     */
    public function parametersProvider()
    {
        return [
            ['domain_parser.cached_domains_list_warmer.class'],
            ['domain_parser.twig.domain_parser_extension.class'],
            ['domain_parser.validator.constraints.is_suffix_valid.class'],
            ['pdp.parser.class'],
            ['pdp.public_suffix_list.class'],
            ['pdp.public_suffix_list_manager.class'],
        ];
    }

    /**
     * Test services definition.
     *
     * @param string $id
     * @param string $class
     *
     * @dataProvider servicesProvider
     * @depends testParameters
     */
    public function testServices($id, $class)
    {
        $this->load();

        $this->assertContainerBuilderHasService($id, $class);
    }

    /**
     * @return array
     */
    public function servicesProvider()
    {
        return [
            ['pdp.parser', Parser::class],
            ['pdp.public_suffix_list', PublicSuffixList::class],
            ['pdp.public_suffix_list_manager', PublicSuffixListManager::class],
            [
                'domain_parser.cached_domains_list_warmer',
                CachedDomainsListWarmer::class,
            ],
            [
                'domain_parser.twig.domain_parser_extension',
                Twig\DomainParserExtension::class,
            ],
            [
                'domain_parser.validator.constraints.is_suffix_valid',
                Constraints\IsSuffixValidValidator::class,
            ],
        ];
    }

    /**
     * Test service with custom cache directory.
     *
     * @depends testServices
     */
    public function testServiceWithCacheDirectory()
    {
        $this->load([
            'cache_dir' => __DIR__,
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'pdp.public_suffix_list_manager',
            0,
            __DIR__
        );
    }
}
