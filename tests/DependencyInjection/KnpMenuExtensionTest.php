<?php

namespace Knp\Bundle\MenuBundle\Tests\DependencyInjection;

use Knp\Bundle\MenuBundle\DependencyInjection\KnpMenuExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KnpMenuExtensionTest extends TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();
        $loader = new KnpMenuExtension();
        $loader->load([[]], $container);
        $this->assertTrue($container->hasDefinition('knp_menu.renderer.list'), 'The list renderer is loaded');
        $this->assertTrue($container->hasDefinition('knp_menu.renderer.twig'), 'The twig renderer is loaded');
        $this->assertEquals('@KnpMenu/menu.html.twig', $container->getParameter('knp_menu.renderer.twig.template'));
        $this->assertFalse($container->hasDefinition('knp_menu.templating.helper'), 'The PHP helper is not loaded');
        $this->assertTrue($container->getDefinition('knp_menu.menu_provider.builder_alias')->hasTag('knp_menu.provider'), 'The BuilderAliasProvider is enabled');
        $this->assertTrue($container->getDefinition('knp_menu.menu_provider.container_aware')->hasTag('knp_menu.provider'), 'The ContainerAwareProvider is enabled');
    }

    public function testEnableTwig()
    {
        $container = new ContainerBuilder();
        $loader = new KnpMenuExtension();
        $loader->load([['twig' => true]], $container);
        $this->assertTrue($container->hasDefinition('knp_menu.renderer.twig'));
        $this->assertEquals('@KnpMenu/menu.html.twig', $container->getParameter('knp_menu.renderer.twig.template'));
    }

    public function testOverwriteTwigTemplate()
    {
        $container = new ContainerBuilder();
        $loader = new KnpMenuExtension();
        $loader->load([['twig' => ['template' => 'foobar']]], $container);
        $this->assertTrue($container->hasDefinition('knp_menu.renderer.twig'));
        $this->assertEquals('foobar', $container->getParameter('knp_menu.renderer.twig.template'));
    }

    public function testDisableTwig()
    {
        $container = new ContainerBuilder();
        $loader = new KnpMenuExtension();
        $loader->load([['twig' => false]], $container);
        $this->assertTrue($container->hasDefinition('knp_menu.renderer.list'));
        $this->assertFalse($container->hasDefinition('knp_menu.renderer.twig'));
    }

    public function testEnsablePhpTemplates()
    {
        $container = new ContainerBuilder();
        $loader = new KnpMenuExtension();
        $loader->load([['templating' => true]], $container);
        $this->assertTrue($container->hasDefinition('knp_menu.templating.helper'));
    }

    public function testDisableBuilderAliasProvider()
    {
        $container = new ContainerBuilder();
        $loader = new KnpMenuExtension();
        $loader->load([['providers' => ['builder_alias' => false]]], $container);
        $this->assertFalse($container->getDefinition('knp_menu.menu_provider.builder_alias')->hasTag('knp_menu.provider'), 'The BuilderAliasProvider is disabled');
        $this->assertTrue($container->getDefinition('knp_menu.menu_provider.container_aware')->hasTag('knp_menu.provider'), 'The ContainerAwareProvider is enabled');
    }

    public function testDisableContainerAwareProvider()
    {
        $container = new ContainerBuilder();
        $loader = new KnpMenuExtension();
        $loader->load([['providers' => ['container_aware' => false]]], $container);
        $this->assertTrue($container->getDefinition('knp_menu.menu_provider.builder_alias')->hasTag('knp_menu.provider'), 'The BuilderAliasProvider is enabled');
        $this->assertFalse($container->getDefinition('knp_menu.menu_provider.container_aware')->hasTag('knp_menu.provider'), 'The ContainerAwareProvider is disabled');
    }
}
