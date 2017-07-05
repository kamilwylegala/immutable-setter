<?php
namespace KamilWylegala\ImmutableSetter;

use Exception;
use ReflectionClass;

class Wither
{
    private $baseObject;

    /** @var array */
    private $constructorSchema;

    public function __construct($baseObject, array $constructorSchema)
    {
        if (!is_object($baseObject)) {
            throw new Exception("Given Wither base object is not an object.");
        }
        $this->baseObject = $baseObject;
        $this->constructorSchema = $constructorSchema;
    }

    public function getInstance($targetProperty, $newValue)
    {
        $targetPropertyGetter = $this->getAvailableGetterOf($targetProperty);
        $currentValue = call_user_func([$this->baseObject, $targetPropertyGetter]);

        if ($currentValue === $newValue) {
            return $this->baseObject;
        }
        
        return $this->constructNewInstance($targetProperty, $newValue);
    }

    private function getAvailableGetterOf($targetProperty)
    {
        $supportedGetters = ["get", "is"];

        foreach($supportedGetters as $getterType) {
            $propertyGetterName = $getterType.ucfirst($targetProperty);
            if (method_exists($this->baseObject, $propertyGetterName)) {
                return $propertyGetterName;
            }
        }
        throw new Exception("Getter for property $targetProperty does not exist.");
    }

    private function constructNewInstance($targetProperty, $newValue)
    {
        $reflectionClass = new ReflectionClass($this->baseObject);

        $mapper = function($property) use ($targetProperty, $newValue) {
            if ($property === $targetProperty) {
                return $newValue;
            }
            return call_user_func([$this->baseObject, $this->getAvailableGetterOf($property)]);
        };

        $constructorArguments = array_map($mapper, $this->constructorSchema);

        return $reflectionClass->newInstanceArgs($constructorArguments);
    }

    /**
     * ModelMapper maps properties by reflection of private fields.
     * This should not be needed when ModelMapper will be switched to public getters only.
     */
   public function __toString()
   {
       return "";
   }
}