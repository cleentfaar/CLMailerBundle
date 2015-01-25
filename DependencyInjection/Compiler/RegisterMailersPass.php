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
        $helperId = 'cl_mailer.mailer_helper';

        if (!$container->hasDefinition($helperId)) {
            return;
        }

        $tag        = 'cl_mailer.mailer';
        $mustExtend = '\CL\Bundle\MailerBundle\Mailer\AbstractMailer';

        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            $mailerDefinition = $container->getDefinition($id);
            $class            = $mailerDefinition->getClass();

            if (!is_subclass_of($class, $mustExtend)) {
                throw new \LogicException(sprintf(
                    'Service tagged with %s must extend the %s class, %s does not do this',
                    $tag,
                    $mustExtend,
                    $class
                ));
            }

            $mailerDefinition->addMethodCall('setHelper', [new Reference($helperId)]);
        }
    }
}
