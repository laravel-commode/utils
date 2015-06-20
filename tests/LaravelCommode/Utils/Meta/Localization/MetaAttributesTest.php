<?php

namespace LaravelCommode\Utils\Meta\Localization;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Translation\Translator;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class MetaAttributesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MetaAttributes|Mock
     */
    private $testInstance;

    /**
     * @var Application|Mock
     */
    private $appMock;

    /**
     * @var Translator|Mock
     */
    private $tranlator;

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

        $this->testInstance = $this->getMockForAbstractClass(MetaAttributes::class, ['_']);

        $this->tranlator = $this->getMock(Translator::class, [], [], '', false);

        parent::setUp();
    }

    public function testGettersAndSetters()
    {
        $this->assertSame('_', $this->testInstance->getLocale());
        $this->assertSame($this->testInstance, $this->testInstance->setLocale('en'));
        $this->assertSame('en', $this->testInstance->getLocale());
        $this->testInstance->setLocale('_');

        $this->assertSame('validation.attributes', $this->testInstance->getLookUpLocation());
        $this->assertSame($this->testInstance, $this->testInstance->setLookUpLocation('en'));
        $this->assertSame('en', $this->testInstance->getLookUpLocation());
        $this->testInstance->setLocale('validation.attributes');
    }

    public function testExistentGet()
    {
        $testArray = [
            '_' => [
                'login' => uniqid()
            ],
            'ru' => [
                'login' => uniqid()
            ],
            'en' => [
                'login' => uniqid()
            ]
        ];

        foreach ($testArray as $localePrefix => $values) {
            foreach ($values as $key => $value) {
                $propertyName = $localePrefix.'_'.$key;
                $this->testInstance->{$propertyName} = null;
                $this->testInstance->{$propertyName} = $value;
            }
        }

        foreach ($testArray as $localePrefix => $values) {
            $this->testInstance->setLocale($localePrefix);

            foreach ($values as $key => $value) {
                $this->assertSame($value, $this->testInstance->{$key});
            }
        }
    }

    public function testLookUp()
    {
        $this->appMock->expects($this->any())->method('make')
            ->will($this->returnCallback(function ($make) {
                switch($make)
                {
                    case 'translator':
                        return $this->tranlator;
                    default:
                        var_dump($make);
                        die('testLookUp');
                }
            }));


        $this->tranlator->expects($this->any())->method('trans')
            ->will($this->returnCallback(function ($trans) {
                switch($trans)
                {
                    case 'validation.attributes.login':
                        return 'Login';
                    case 'validation.attributes.not_existent':
                        return $trans;
                }
            }));

        $this->assertSame('Login', $this->testInstance->login);
        $this->assertSame('not_existent', $this->testInstance->not_existent);
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
