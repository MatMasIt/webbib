import { Query } from "../classes/Query.class";

export interface CRUDL {
    load(id: bigint): void;
    list(q: Query): Promise<object>;
    create(): Promise<boolean>;
    save(): Promise<boolean>;
    delete(): Promise<boolean>;
}