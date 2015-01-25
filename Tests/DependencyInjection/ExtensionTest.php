<?php

namespace CL\Bundle\MailerBundle\Tests\DependencyInjection;

use CL\Bundle\MailerBundle\DependencyInjection\CLMailerExtension;
use CL\Bundle\MailerBundle\Tests\TestingTrait;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class ExtensionTest extends AbstractExtensionTestCase
{
    use TestingTrait;

    public function testParameters()
    {
        $config = [
            'defaults' => $this->getTestingDefaults(),
            'types'    => [
                'acme_welcome' => $this->getTestingType(),
            ],
            'twig'     => false,
        ];

        $this->load($config);

        $this->assertContainerBuilderHasParameter('cl_mailer.defaults', $config['defaults']);
        $this->assertContainerBuilderHasParameter('cl_mailer.types', $config['types']);
        $this->assertContainerBuilderHasParameter('cl_mailer.twig', $config['twig']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [new CLMailerExtension()];
    }
}
