<?php

namespace Tests\Chaplean\Bundle\RedmineClientBundle\DependencyInjection;

use Chaplean\Bundle\RedmineClientBundle\DependencyInjection\ChapleanRedmineClientExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ChapleanRedmineClientExtensionTest.
 *
 * @package   Tests\Chaplean\Bundle\RedmineClientBundle\DependencyInjection
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ChapleanRedmineClientExtensionTest extends TestCase
{
    /**
    /**
     * @covers \Chaplean\Bundle\RedmineClientBundle\DependencyInjection\ChapleanRedmineClientExtension::load()
     *
     * @return void
     */
    public function testConfigIsLoadedInParameters()
    {
        $container = new ContainerBuilder();
        $loader = new ChapleanRedmineClientExtension();
        $loader->load([['api' => ['url' => 'url', 'access_token' => 'token']]], $container);

        $this->assertEquals('%chaplean_redmine_client.url%', $container->getParameter('chaplean_redmine_client.api.url'));
        $this->assertEquals('%chaplean_redmine_client.access_token%', $container->getParameter('chaplean_redmine_client.api.access_token'));
    }
}
