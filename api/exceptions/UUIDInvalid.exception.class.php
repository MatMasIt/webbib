<?php

/**
 * Attempted to use invalid value for uuid namespace
 */
class UUIDInvalid extends Exception
{
    public function __construct()
    {
        return parent::__construct("Attempted to use invalid value for uuid namespace", 0);
    }
}
