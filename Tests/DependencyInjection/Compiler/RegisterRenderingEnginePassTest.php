<?php

namespace CL\Bundle\MailerBundle\Tests\DependencyInjection\Compiler;

use CL\Bundle\MailerBundle\DependencyInjection\Compiler\RegisterRenderingEnginePass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterRenderingEnginePassTest extends AbstractCompilerPassTestCase
{
    const MESSAGE_RENDERER_ID = 'cl_mailer.message_renderer';
    const TWIG_ENGINE_ID      = 'templating.engine.twig';
    const PHP_ENGINE_ID       = 'templating.engine.php';

    public function testIfCompilerPassAddsPhpEngineIfTwigEnabledButNotAvailable()
    {
        $this->prepare();
        $this->setTwigEnabled(true);

        // NOTE: we are intentionally NOT creating the twig engine for this test...
        // $twigEngineDefinition= $this->createTwigEngineDefinition();

        $this->assertPhpIsUsed();
    }

    public function testIfCompilerPassAddsPhpEngineIfTwigNotEnabled()
    {
        $this->prepare();
        $this->setTwigEnabled(false);

        // we intentionally make twig available, to test that
        // the "cl_mailer.twig=false" parameter still ignores it and uses php instead
        $this->createTwigEngineDefinition();

        $this->assertPhpIsUsed();
    }

    public function testIfCompilerPassAddsTwigEngineIfTwigEnabledAndAvailable()
    {
        $this->prepare();
        $this->setTwigEnabled(true);

        // We need the twig engine to be able to use it obviously
        $this->createTwigEngineDefinition();

        $this->assertTwigIsUsed();
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterRenderingEnginePass());
    }

    private function prepare()
    {
        $messageRendererBuilder = $this->getMockBuilder('CL\Bundle\MailerBundle\Mailer\MessageRenderer');
        $messageRendererBuilder->disableOriginalConstructor();

        $messageRendererDefinition = new Definition(get_class($messageRendererBuilder->getMock()));

        $this->setDefinition(self::MESSAGE_RENDERER_ID, $messageRendererDefinition);

        $this->createPhpEngineDefinition();
    }

    private function assertPhpIsUsed()
    {
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            self::MESSAGE_RENDERER_ID,
            0,
            new Reference(self::PHP_ENGINE_ID)
        );
    }

    private function assertTwigIsUsed()
    {
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            self::MESSAGE_RENDERER_ID,
            0,
            new Reference(self::TWIG_ENGINE_ID)
        );
    }

    /**
     * @return string
     */
    private function createTwigEngineDefinition()
    {
        return $this->createEngineDefinition(self::TWIG_ENGINE_ID, 'Symfony\Bundle\TwigBundle\TwigEngine');
    }

    /**
     * @return string
     */
    private function createPhpEngineDefinition()
    {
        return $this->createEngineDefinition(self::PHP_ENGINE_ID, 'Symfony\Component\Templating\PhpEngine');
    }

    /**
     * @param string $id
     * @param string $class
     *
     * @return string
     */
    private function createEngineDefinition($id, $class)
    {
        $builder = $this->getMockBuilder($class);
        $builder->disableOriginalConstructor();

        $engineDefinition = new Definition(get_class($builder->getMock()));
        $this->setDefinition($id, $engineDefinition);

        return $id;
    }

    /**
     * @param bool $enabled
     */
    private function setTwigEnabled($enabled)
    {
        $this->container->setParameter('cl_mailer.twig', $enabled);
    }
}
