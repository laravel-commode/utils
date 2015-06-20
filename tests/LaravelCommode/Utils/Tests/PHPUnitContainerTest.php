<?php

namespace LaravelCommode\Utils\Tests;

use Illuminate\Container\Container;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class PHPUnitContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_TestCase|Mock
     */
    private $testInstance;

    /**
     * @return array|string[]
     */

    protected function setUp()
    {
        $this->testInstance = new PHPUnitContainer();
        parent::setUp();
    }

    public function testSetUp()
    {
        $reflectionSetUp = new \ReflectionMethod($this->testInstance, 'setUp');
        $reflectionTearDown = new \ReflectionMethod($this->testInstance, 'tearDown');
        $reflectionGetMock = new \ReflectionMethod($this->testInstance, 'getApplicationMock');


        $reflectionSetUp->setAccessible(true);
        $reflectionGetMock->setAccessible(true);
        $reflectionTearDown->setAccessible(true);

        $reflectionSetUp->invoke($this->testInstance);

        $this->assertSame(Container::getInstance(), $reflectionGetMock->invoke($this->testInstance));

        $reflectionTearDown->invoke($this->testInstance);

        $this->assertNull(Container::getInstance());

        try {
            $reflectionGetMock->invoke($this->testInstance);
        } catch (\Exception $e) {
            $this->assertSame(
                'Undefined property: LaravelCommode\Utils\Tests\PHPUnitContainer::$applicationMock',
                $e->getMessage()
            );
        }
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        parent::tearDown();
    }
}
