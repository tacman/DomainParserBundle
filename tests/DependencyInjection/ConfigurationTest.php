<?php

namespace EmanueleMinotto\DomainParserBundle\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * @covers EmanueleMinotto\DomainParserBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtension(): ExtensionInterface
    {
        return new DomainParserExtension();
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * Test configuration formats.
     */
    public function testConfigurationFormats()
    {
        $expectedConfiguration = ['cache_dir' => '%kernel.cache_dir%/domain_parser', 'http_adapter' => 'test'];

        $sources = [
            __DIR__.'/Fixtures/config.yml',
            __DIR__.'/Fixtures/config.xml',
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }
}
