<?php

/**
 * Performed operation incompatible with database specification
 */
class OutOfDatabaseSpecification extends Exception
{
    public function __construct()
    {
        return parent::__construct("Performed operation incompatible with database specification", 0);
    }
}
