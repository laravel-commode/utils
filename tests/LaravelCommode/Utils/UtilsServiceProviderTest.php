<?php

namespace LaravelCommode\Utils;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use LaravelCommode\Utils\Meta\Localization\MetaManager;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class UtilsServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UtilsServiceProvider
     */
    private $testInstance;

    /**
     * @var Application|Mock
     */
    private $appMock;

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

        $this->appMock = $this->getMock(
            Application::class,
            array_merge($appMethods, $containerMethods, ['getLocale'])
        );

        $this->testInstance = new UtilsServiceProvider($this->appMock);

        Container::setInstance($this->appMock);

        parent::setUp();
    }

    public function testRegister()
    {
        $this->appMock->expects($this->any())->method('singleton')
            ->will($this->returnCallback(function ($bindTo, $bound) {
                switch($bindTo)
                {
                    case UtilsServiceProvider::PROVIDES_META_MANAGER:
                        $this->assertTrue($bound() instanceof MetaManager);
                        break;
                }
            }));

        $this->testInstance->registering();
    }

    public function testLaunching()
    {
        $this->testInstance->launching();
    }

    protected function tearDown()
    {
        $reflectionProperty = new \ReflectionProperty(Container::class, 'instance');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null);
        $reflectionProperty->setAccessible(false);

        unset($this->testInstance, $this->appMock);
        parent::tearDown();
    }
}
