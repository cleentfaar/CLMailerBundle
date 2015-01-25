<?php

namespace CL\Bundle\MailerBundle\Tests\DependencyInjection;

use CL\Bundle\MailerBundle\DependencyInjection\Configuration;
use CL\Bundle\MailerBundle\Tests\TestingTrait;
use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    use TestingTrait;

    public function testValuesAreValid()
    {
        $this->assertConfigurationIsValid(
            [
                []
            ]
        );

        $this->assertConfigurationIsValid(
            [
                [
                    'defaults' => $this->getTestingDefaults()
                ]
            ]
        );

        $this->assertConfigurationIsValid(
            [
                [
                    'defaults' => $this->getTestingDefaults(),
                    'types'    => [
                        'acme_welcome' => $this->getTestingType(),
                    ],
                    'twig'     => false,
                ]
            ]
        );
    }

    public function testValuesAreInvalidIfBadDefaultsAreProvided()
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'defaults' => 'foobar', // bad type of value (string should be array)
                ]
            ],
            'defaults' // (part of) the expected exception message
        );

        $this->assertConfigurationIsInvalid(
            [
                [
                    'defaults' => [
                        'foo' => 'bar' // unknown option for defaults
                    ],
                ]
            ],
            'defaults' // (part of) the expected exception message
        );

        $this->assertConfigurationIsInvalid(
            [
                [
                    'twig' => 5
                ]
            ],
            'twig' // (part of) the expected exception message
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}

