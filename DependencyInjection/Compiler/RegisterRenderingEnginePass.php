<?php

namespace CL\Bundle\MailerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterRenderingEnginePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $messageRendererId = 'cl_mailer.message_renderer';
        $twigId            = 'templating.engine.twig';
        $phpId             = 'templating.engine.php';

        if (!$container->hasDefinition($phpId) || !$container->hasDefinition($messageRendererId)) {
            return;
        }

        if (!$container->getParameter('cl_mailer.twig') || !$container->hasDefinition($twigId)) {
            $rendererId = $phpId;
        } else {
            $rendererId = $twigId;
        }

        $container->getDefinition($messageRendererId)->setArguments([new Reference($rendererId)]);
    }
}
