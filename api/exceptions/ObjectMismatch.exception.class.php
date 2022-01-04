<?php

/**
 * Attempted to load object incompatible with class
 */
class ObjectMismatch extends Exception
{
    public function __construct()
    {
        return parent::__construct("Attempted to load object incompatible with class", 0);
    }
}
