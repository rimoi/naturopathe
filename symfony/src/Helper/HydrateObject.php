<?php

declare(strict_types=1);

namespace App\Helper;

class HydrateObject
{
    /**
     * @param mixed $value
     *
     * @throws \ReflectionException
     */
    public static function setProperty(object $object, string $property, $value): void
    {
        $reflectionProperty = new \ReflectionProperty(\get_class($object), $property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }

    /**
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public static function getProperty(object $object, string $property)
    {
        $reflectionProperty = new \ReflectionProperty(\get_class($object), $property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    /**
     * @param mixed ...$args
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public static function callMethod(object $object, string $method, ...$args)
    {
        $reflectionObject = new \ReflectionObject($object);
        $methodObject = $reflectionObject->getMethod($method);
        $methodObject->setAccessible(true);

        return $methodObject->invoke($object, ...$args);
    }

    /**
     * @param class-string $class
     */
    public static function createWithoutConstructor($class): object
    {
        $reflectionObject = new \ReflectionClass($class);

        return $reflectionObject->newInstanceWithoutConstructor();
    }

    /**
     * @param mixed $to   Entity that we are going to fill
     * @param mixed $from Entity to which we get the information
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public static function hydrateObjectByMethod($to, $from)
    {
        $reflect = new \ReflectionClass($from);
        $methods = $reflect->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if (substr($method->getName(), 0, 3) === 'get') {
                $setter = sprintf('s%s', substr($method->getName(), 1));
                $getter = $method->getName();
                if (method_exists($to, $setter)) {
                    $to->$setter($from->$getter());
                }
            }
        }

        return $to;
    }

    /**
     * @param mixed $to   Entity that we are going to fill
     * @param mixed $from Entity to which we get the information
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public static function hydrateObjectByProperty($to, $from)
    {
        $reflect = new \ReflectionClass($from);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            $name = $prop->getName();

            if (property_exists($to, $name)) {
                $value = self::getProperty($from, $name);
                self::setProperty($to, $name, $value);
            }
        }

        return $to;
    }
}
