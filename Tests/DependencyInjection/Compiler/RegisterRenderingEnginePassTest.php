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

        $this->assertEngineUsed(self::PHP_ENGINE_ID);
    }

    public function testIfCompilerPassAddsPhpEngineIfTwigNotEnabled()
    {
        $this->prepare();
        $this->setTwigEnabled(false);

        // we intentionally make twig available, to test that
        // the "cl_mailer.twig=false" parameter still ignores it and uses php instead
        $this->createTwigEngineDefinition();

        $this->assertEngineUsed(self::PHP_ENGINE_ID);
    }

    public function testIfCompilerPassAddsTwigEngineIfTwigEnabledAndAvailable()
    {
        $this->prepare();
        $this->setTwigEnabled(true);

        // We need the twig engine to be able to use it obviously
        $this->createTwigEngineDefinition();

        $this->assertEngineUsed(self::TWIG_ENGINE_ID);
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterRenderingEnginePass());
    }

    private function prepare()
    {
        $this->createMessageRendererDefinition();
        $this->createPhpEngineDefinition();
    }

    private function assertEngineUsed($id)
    {
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            self::MESSAGE_RENDERER_ID,
            0,
            new Reference($id)
        );
    }

    /**
     * @return string
     */
    private function createTwigEngineDefinition()
    {
        return $this->createDefinition(self::TWIG_ENGINE_ID, 'Symfony\Bundle\TwigBundle\TwigEngine');
    }

    /**
     * @return string
     */
    private function createPhpEngineDefinition()
    {
        return $this->createDefinition(self::PHP_ENGINE_ID, 'Symfony\Component\Templating\PhpEngine');
    }

    /**
     * @param string $id
     * @param string $class
     *
     * @return string
     */
    private function createDefinition($id, $class)
    {
        $builder = $this->getMockBuilder($class);
        $builder->disableOriginalConstructor();

        $this->setDefinition($id, new Definition(get_class($builder->getMock())));

        return $id;
    }

    /**
     * @return string
     */
    private function createMessageRendererDefinition()
    {
        return $this->createDefinition(self::MESSAGE_RENDERER_ID, 'CL\Bundle\MailerBundle\Mailer\MessageRenderer');
    }

    /**
     * @param bool $enabled
     */
    private function setTwigEnabled($enabled)
    {
        $this->container->setParameter('cl_mailer.twig', $enabled);
    }
}
