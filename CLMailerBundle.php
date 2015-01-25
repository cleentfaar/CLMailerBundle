<?php

namespace CL\Bundle\MailerBundle;

use CL\Bundle\MailerBundle\DependencyInjection\Compiler\RegisterMailersPass;
use CL\Bundle\MailerBundle\DependencyInjection\Compiler\RegisterRenderingEnginePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CLMailerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterMailersPass());
        $container->addCompilerPass(new RegisterRenderingEnginePass());
    }
}
