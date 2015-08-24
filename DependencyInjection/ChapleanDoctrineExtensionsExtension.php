<?php

namespace Chaplean\Bundle\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\ClassLoader\Psr4ClassLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class ChapleanDoctrineExtensionsExtension.
 *
 * @package   Chaplean\Bundle\DoctrineExtensionsBundle\DependencyInjection
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class ChapleanDoctrineExtensionsExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $classLoader = new Psr4ClassLoader();
        $classLoader->addPrefix('DoctrineExtensions\\', $container->getParameter('kernel.root_dir') . '/../vendor/beberlei/DoctrineExtensions/src/');
        $classLoader->register();
    }
}
