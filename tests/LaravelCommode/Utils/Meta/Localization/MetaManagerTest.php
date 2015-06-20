<?php

namespace LaravelCommode\Utils\Meta\Localization;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use PHPUnit_Framework_TestCase;

class MetaManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Application|Mock
     */
    private $appMock;

    /**
     * @var MetaManager
     */
    private $testInstance;

    /**
     * @var MetaAttributes|Mock
     */
    private $metaAttributesMock;

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

        Container::setInstance($this->appMock);

        $this->metaAttributesMock = $this->getMockForAbstractClass(MetaAttributes::class);

        $this->testInstance = new MetaManager();

        parent::setUp();
    }

    public function testRegisterMeta()
    {
        $name = uniqid('name');
        $this->testInstance->registerMetaAttributes($name, $mockClass = get_class($this->metaAttributesMock));
        $this->assertSame($mockClass, get_class($this->testInstance->getMetaAttributes($name)));

        try {
            $this->testInstance->registerMetaAttributes($name = uniqid(), 'stdClass');
        } catch (\Exception $e) {
            $this->assertTrue(
                $e instanceof \InvalidArgumentException
            );
            $this->assertSame(
                'Meta attribute but be extended from '.MetaAttributes::class.'. stdClass was given.',
                $e->getMessage()
            );
        }
    }

    protected function tearDown()
    {
        $reflectionProperty = new \ReflectionProperty(Container::class, 'instance');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null);
        $reflectionProperty->setAccessible(false);

        unset($this->testInstance, $this->metaAttributesMock, $this->appMock);
        parent::tearDown();
    }
}
