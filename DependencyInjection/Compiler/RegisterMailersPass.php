<?php

namespace CL\Bundle\MailerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception as Da;
use Symfony\Component\DependencyInjection\Reference;

class RegisterMailersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registryId     = 'cl_mailer.util.mailer_registry';
        $types          = $container->getParameter('cl_mailer.types');
        $defaultOptions = $container->getParameter('cl_mailer.defaults');

        if (!$container->hasDefinition($registryId)) {
            return;
        }

        $registryDefinition = $container->getDefinition($registryId);

        foreach ($types as $type => $options) {
            $mailerId         = $options['mailer'];
            $mailerDefinition = $container->getDefinition($mailerId);
            $mailerClass      = $mailerDefinition->getClass();
            $mustImplement    = 'CL\Bundle\MailerBundle\Mailer\MailerInterface';
            $options          = array_merge($defaultOptions, $options);

            unset($options['mailer']);

            if (!in_array($mustImplement, class_implements($mailerClass))) {
                throw new \InvalidArgumentException(sprintf(
                    'Can\'t register mailer %s; it must implement %s',
                    $mailerClass,
                    $mustImplement
                ));
            }


            $registryDefinition->addMethodCall('register', [$type, new Reference($mailerId), $options]);
        }
    }
}
