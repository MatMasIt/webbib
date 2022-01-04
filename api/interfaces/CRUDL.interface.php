<?php

/**
 * Interface Specifying [CRUDL](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete) methods
 */
interface CRUDL
{

    public function load(int $id): void;
    public function list(Query $q): ApiResult;
    public function create(): ApiResult;
    public function save(): ApiResult;
    public function delete(): ApiResult;
}
