<?php


namespace Sheel\Map\MapDetails;


use ReflectionClass;

abstract class AbstractMap
{

    /**
     * Returns the class name
     * @return string
     */
    public static function getClassName() {
        return get_called_class();
    }


    /**
     *
     * Returns constant name, when given the constant value and class
     * @param $constantValue
     * @param $class
     * @return string
     * @throws \ReflectionException
     */
    public static function toString( $constantValue,  $class ){

        $RC = new ReflectionClass( $class );
        $constants = $RC->getConstants();

        $flipConstants = array_flip( $constants );

        return strtolower($flipConstants[ $constantValue ] ) ;

    }

}
