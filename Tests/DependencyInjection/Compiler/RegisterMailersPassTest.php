<?php

namespace CL\Bundle\MailerBundle\Tests\DependencyInjection\Compiler;

use CL\Bundle\MailerBundle\DependencyInjection\Compiler\RegisterMailersPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterMailersPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterMailersPass());
    }

    public function testIfCompilerPassAddsMethodCallsToApplicableServices()
    {
        $tag           = 'cl_mailer.mailer';
        $helperBuilder = $this->getMockBuilder('CL\Bundle\MailerBundle\Mailer\MailerHelper');
        $helperBuilder->disableOriginalConstructor();

        $helperDefinition = new Definition(get_class($helperBuilder->getMock()));
        $mailerDefinition = new Definition(get_class($this->getMock('CL\Bundle\MailerBundle\Mailer\AbstractMailer')));
        $mailerDefinition->addTag($tag);

        $mailerId = 'acme_mailer';
        $helperId = 'cl_mailer.mailer_helper';

        $this->setDefinition($helperId, $helperDefinition);
        $this->setDefinition($mailerId, $mailerDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            $mailerId,
            'setHelper',
            [
                new Reference($helperId)
            ]
        );
    }
}
