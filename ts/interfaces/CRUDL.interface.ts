import { Query } from "../classes/Query.class";

export interface CRUDL {
    load(id: number): Promise<boolean>;
    list(q: Query): Promise<object>;
    create(): Promise<boolean>;
    save(): Promise<boolean>;
    delete(): Promise<boolean>;
}