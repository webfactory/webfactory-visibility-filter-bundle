<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures\Kernel;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Webfactory\VisibilityFilterBundle\VisibilityFilterBundle;

class TestKernel extends Kernel
{
    /**
     * @var callable(KernelInterface $kernel): void[]
     */
    private $onKernelBootHandlers = [];

    public function registerBundles()
    {
        return [new FrameworkBundle(), new DoctrineBundle(), new VisibilityFilterBundle()];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function boot()
    {
        parent::boot();

        foreach ($this->onKernelBootHandlers as $bootHandler) {
            $bootHandler($this);
        }
    }

    /**
     * @param callable(KernelInterface $kernel): void $onKernelBootHandler
     */
    public function onKernelBoot(callable $onKernelBootHandler): void
    {
        $this->onKernelBootHandlers[] = $onKernelBootHandler;
    }
}
