<?php

namespace EmanueleMinotto\DomainParserBundle\CacheWarmer;

use Pdp\HttpAdapter\HttpAdapterInterface;
use Pdp\PublicSuffixListManager;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

/**
 * Refresh domains list on cache warmup.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class CachedDomainsListWarmer extends CacheWarmer
{
    /**
     * Pdp Public Suffix List Manager HTTP adapter.
     */
    private ?\Pdp\HttpAdapter\HttpAdapterInterface $httpAdapter;

    /**
     * Cache warmer constructor with optional dependency.
     *
     * @param HttpAdapterInterface|null $httpAdapter
     */
    public function __construct(HttpAdapterInterface $httpAdapter = null)
    {
        $this->httpAdapter = $httpAdapter;
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $manager = new PublicSuffixListManager($cacheDir);
        if (null !== $this->httpAdapter) {
            $manager->setHttpAdapter($this->httpAdapter);
        }

        $manager->refreshPublicSuffixList();
    }
}
