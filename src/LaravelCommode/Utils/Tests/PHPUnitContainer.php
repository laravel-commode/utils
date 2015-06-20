<?php

namespace LaravelCommode\Utils\Tests;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as IContainer;
use Illuminate\Contracts\Foundation\Application;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class PHPUnitContainer extends PHPUnit_Framework_TestCase
{
    /**
     * @var Application|IContainer|Mock
     */
    private $applicationMock;

    /**
     * @return array|string[]
     */
    protected function applicationMocksMethods()
    {
        return [];
    }

    /**
     * @return IContainer|Application|Mock
     */
    protected function getApplicationMock()
    {
        return $this->applicationMock;
    }

    protected function setUp()
    {
        $appMethods = [
            'version', 'basePath', 'environment', 'isDownForMaintenance', 'registerConfiguredProviders',
            'register', 'registerDeferredProvider', 'boot', 'booting', 'booted', 'getCachedCompilePath',
            'getCachedServicesPath'
        ];

        $containerMethods = [
            'bound', 'alias', 'tag', 'tagged', 'bind', 'bindIf',
            'singleton', 'extend', 'instance', 'when', 'make',
            'call', 'resolved', 'resolving', 'afterResolving'
        ];

        $this->applicationMock = $this->getMock(
            Application::class,
            array_merge($appMethods, $containerMethods, $this->applicationMocksMethods())
        );

        Container::setInstance($this->applicationMock);

        parent::setUp();
    }

    protected function tearDown()
    {
        $reflectionProperty = new \ReflectionProperty(Container::class, 'instance');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null);
        $reflectionProperty->setAccessible(false);

        unset($this->applicationMock);

        parent::tearDown();
    }
}
