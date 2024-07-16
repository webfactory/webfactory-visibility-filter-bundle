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
    public function registerBundles(): iterable
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
}
