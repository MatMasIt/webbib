interface CRUDL {
    load(id: bigint): void;
    list(q: Query): ApiResult;
    create(): ApiResult;
    save(): ApiResult;
    delete(): ApiResult;
}