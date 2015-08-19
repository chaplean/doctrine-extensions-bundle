<?php

namespace Chaplean\Bundle\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\ClassLoader\Psr4ClassLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ChapleanDoctrineExtensionsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $classLoader = new Psr4ClassLoader();
        $classLoader->addPrefix('DoctrineExtensions\\', $container->getParameter('kernel.root_dir') . '/../vendor/beberlei/DoctrineExtensions/src/');
        $classLoader->register();
    }
}
