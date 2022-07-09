<?php

namespace EmanueleMinotto\DomainParserBundle\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;

/**
 * @covers EmanueleMinotto\DomainParserBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtension()
    {
        return new DomainParserExtension();
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
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
