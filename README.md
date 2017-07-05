# Immutable Setter

Tiny utillity class that helps you making your classes immutable by adding *wither* methods. Inspired by Lombok's @Wither annotations.

It lets you easily add `witherX` methods to clone object with modified one property.

## Installation

TODO: Add package to packagist.

## Usage

1. Add `wither` field to your class.
2. Assign `new Wither($this, ["arg1", "arg2"])` to this field and provide constructor schema with proper order of arguments.
3. Add public `withArg1` method to your class and put:
```
return $this->wither->getInstance("arg1", $newArg1)
```
4. Running `$valueObject->withArg1($newArg1)` will create copy of your object with changed `$arg1` field.

### Example 

```php
use KamilWylegala\ImmutableSetter\Wither;

class Person
{

    private $name;
    private $age;

    private $wither;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;

        $this->wither = new Wither($this, ["name", "age"]);
    }

    public function withName($newName)
    {
        return $this->wither->getInstance("name", $newName);   
    }

}
```

## Tests

Install dev dependencies and run in root:
```
$ vendor/bin/phpunit
```

## Licence

MIT
