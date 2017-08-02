<?php

namespace Chaplean\Bundle\DoctrineExtensionsBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class DoctrineExtensionListener.
 *
 * @package   App\Bundle\RestBundle\Listener
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     0.1.0
 */
class DoctrineExtensionListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface|null $container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return void
     */
    public function onLateKernelRequest(GetResponseEvent $event)
    {
        $translatable = $this->container->get('gedmo.listener.translatable');
        $translatable->setTranslatableLocale($event->getRequest()->getLocale());
    }

    /**
     * @return void
     */
    public function onConsoleCommand()
    {
        $this->container->get('gedmo.listener.translatable')->setTranslatableLocale($this->container->get('translator')->getLocale());
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $loggable = $this->container->get('gedmo.listener.loggable');
            $loggable->setUsername($securityContext->getToken()->getUsername());
        }
    }
}
