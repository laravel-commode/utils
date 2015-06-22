<?php

namespace LaravelCommode\Utils\DataStructures;

use Exception;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use stdClass;

class TypeStackTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TypeStack|Mock
     */
    private $testInstance;

    protected function setUp()
    {
        $this->generateInstance();
        parent::setUp();
    }

    protected function generateInstance()
    {
        return $this->testInstance = $this->getMockForAbstractClass(TypeStack::class);
    }

    public function testInstance()
    {
        $this->generateInstance();

        $this->testInstance->expects($this->any())->method('getType')
            ->will($this->returnValue(self::class));

        $this->testInstance->add(0, $this);
        $this->testInstance->offsetSet(0, $this);
        $this->testInstance->push($this);
        $this->testInstance->unshift($this);

        try {
            $this->testInstance->offsetSet(0, $this->testInstance);
        } catch (Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }
    }

    public function testValue()
    {
        $this->generateInstance();

        $this->testInstance->expects($this->any())->method('getType')
            ->will($this->returnValue(stdClass::class));

        $testArray = [(object)['a' => 1], (object)['a' => 2], (object)['a' => 3], (object)['a' => 4]];

        foreach ($testArray as $array) {
            $this->testInstance->push($array);
        }

        foreach ($this->testInstance as $key => $value) {
            $this->assertSame($testArray[$key], $value);
        }

        $this->testInstance->pop();

        $this->assertCount(3, $this->testInstance);
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        parent::tearDown();
    }
}
