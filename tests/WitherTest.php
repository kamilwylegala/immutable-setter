<?php
namespace KamilWylegala\ImmutableSetter;

use PHPUnit_Framework_TestCase;
use Exception;

class WitherTest extends PHPUnit_Framework_TestCase
{
    private $testObject;

    /** @var Wither */
    private $wither;

    public function setUp()
    {
        $this->testObject = new TestSubject("John", 28, false);
        $this->wither = new Wither($this->testObject,
            ["name", "age", "verified"]);
    }

    /** @test */
    public function should_return_the_same_instance_of_given_object()
    {
        $this->assertThat($this->wither->getInstance("name", "John"),
            $this->identicalTo($this->testObject));
    }

    /** @test */
    public function should_return_new_instance_with_changed_name()
    {
        $newInstance = $this->wither->getInstance("name", "Bill");

        $this->assertNotSame($this->testObject, $newInstance);
        $this->assertThat($newInstance,
            $this->equalTo(new TestSubject("Bill", 28, false)));
    }

    /** @test */
    public function should_return_new_instance_for_boolean_getter()
    {
        $newInstance = $this->wither->getInstance("verified", true);
        $this->assertNotSame($this->testObject, $newInstance);
        $this->assertThat($newInstance,
            $this->equalTo(new TestSubject("John", 28, true)));
    }

    /** @test */
    public function should_throw_exception_if_getter_does_not_exist()
    {
        $this->setExpectedException(Exception::class);
        $this->wither->getInstance("weight", 80);
    }

    /** @test */
    public function should_throw_exception_when_given_base_object_is_not_object()
    {
        $this->setExpectedException(Exception::class);
        new Wither([], ["property1", "property2"]);
    }

}

class TestSubject
{
    private $name;
    private $age;
    private $verified;

    public function __construct($name, $age, $verified)
    {
        $this->name = $name;
        $this->age = $age;
        $this->verified = $verified;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function isVerified()
    {
        return $this->verified;
    }
}