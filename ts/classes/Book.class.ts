import { Authentication } from "../interfaces/Authentication.interface";
import { CRUDL } from "../interfaces/CRUDL.interface";
import { ObjSerialize } from "../interfaces/ObjSerialize.interface";
import { Validation } from "../interfaces/Validation.interface";
import { Query } from "./Query.class";

export class Book implements CRUDL, Validation, ObjSerialize {
    version = "1.0.0";
    id: number;
    lastEdit: number;
    created: number;
    genre: string;
    title: string;
    authors: string;
    editor: string;
    serie: string;
    language: string;
    topic: string;
    isbn: string;
    publicNotes: string;
    privateNotes: string;
    location: string;
    date: string;
    inventory: string;
    noPages: string;
    bibliographicLevel: string;
    dewey: string;
    publishingCountry: string;
    editorPlace: string;
    curator: string;
    type: string;
    translation: string;
    description: string;
    identification: string;
    isPublic: boolean;
    load(id: number): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    list(q: Query): Promise<object> {
        throw new Error("Method not implemented.");
    }
    create(): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    save(): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    delete(): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    validate(): boolean {
        throw new Error("Method not implemented.");
    }
    toObj(): object {
        throw new Error("Method not implemented.");
    }
    fromObj(o: object): void {
        throw new Error("Method not implemented.");
    }
}