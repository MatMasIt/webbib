<?php

/**
 * Interface specifying serialization-related operations
 */
interface ObjSerialize
{
    public function toObj(): array;
    public function fromObj(array $object): void;
}
