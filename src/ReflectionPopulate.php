<?php

namespace Solution10\Data;

use Solution10\Data\Util\Str;

trait ReflectionPopulate
{
    /**
     * @param   object  $object
     * @param   array   $data
     * @return  object
     */
    public function populateWithReflection($object, array $data)
    {
        $ref = new \ReflectionClass($object);
        foreach ($data as $k => $v) {
            $setterName = Str::snakeToCamel($k, 'set');
            $camelProperty = Str::snakeToCamel($k);
            if (method_exists($object, $setterName)) {
                $object->$setterName($v);
            } elseif (property_exists($object, $k) || property_exists($object, $camelProperty)) {
                $propName = (property_exists($object, $k))? $k : $camelProperty;
                $prop = $ref->getProperty($propName);
                $prop->setAccessible(true);
                $prop->setValue($object, $v);
            }
        }
        return $object;
    }
}
