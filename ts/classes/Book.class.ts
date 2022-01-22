import { Authentication } from "../interfaces/Authentication.interface";
import { CRUDL } from "../interfaces/CRUDL.interface";
import { ObjSerialize } from "../interfaces/ObjSerialize.interface";
import { Validation } from "../interfaces/Validation.interface";
import { Api } from "./Api.class";
import { Query } from "./Query.class";
import { User } from "./User.class";

export class Book implements CRUDL, Validation, ObjSerialize {
    classN = "Book";
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
    api: Api;
    u: User;
    constructor(api: Api, u: User) {
        this.api = api;
        this.u = u;
    }
    load(id: number): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "books.get",
                "sessionToken": this.u.token ? this.u.token : this.token,
                "id": id
            });
            req.then(function ok(data) {
                this.fromObj(data["data"]);
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    }
    list(q: Query): Promise<object> {
        return new Promise<object>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.list",
                "sessionToken": this.u.token,
                "query": q.toObj()
            });
            req.then(function ok(data) {
                resolve(data["data"]);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    }
    create(): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "books.create",
                "sessionToken": this.u.token,
                "object": this.toObj()
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    }
    save(): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "books.edit",
                "sessionToken": this.u.token,
                "object": this.toObj()
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    }
    delete(): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.remove",
                "sessionToken": this.u.token,
                "id": this.id
            });
            req.then(function ok(data) {
                this.fromObj(data["data"]);
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    }
    validate(): boolean {
        if (!this.title) return false;
        if (this.lastEdit < this.created) return false;
        return true;
    }
    toObj(): object {
        return {
            "type": this.classN,
            "version": this.version,
            "id": this.id,
            "data": {
                "genre": this.genre,
                "title": this.title,
                "authors": this.authors,
                "editor": this.editor,
                "serie": this.serie,
                "language": this.language,
                "topic": this.topic,
                "isbn": this.isbn,
                "publicNotes": this.publicNotes,
                "privateNotes": this.privateNotes,
                "location": this.location,
                "date": this.date,
                "inventory": this.inventory,
                "noPages": this.noPages,
                "bibliographicalLevel": this.bibliographicLevel,
                "dewey": this.dewey,
                "publishingCountry": this.publishingCountry,
                "editorPlace": this.editorPlace,
                "curator": this.curator,
                "type": this.type,
                "translation": this.translation,
                "description": this.description,
                "identification": this.identification
            },
            "isPublic": this.isPublic,
            "lastEdit": this.lastEdit,
            "created": this.created
        };
    }
    fromObj(o: object): void {
        if (o["type"] != this.classN || o["version"] != this.version) throw new ObjectMismatch();
        this.id = parseInt(o["id"]);
        this.genre = String(o["data"]["genre"]);
        this.title = String(o["data"]["title"]);
        this.authors = String(o["data"]["authors"]);
        this.editor = String(o["data"]["editor"]);
        this.serie = String(o["data"]["serie"]);
        this.language = String(o["data"]["language"]);
        this.topic = String(o["data"]["topic"]);
        this.isbn = String(o["data"]["isbn"]);
        this.publicNotes = String(o["data"]["publicNotes"]);
        this.privateNotes = String(o["data"]["privateNotes"]);
        this.location = String(o["data"]["location"]);
        this.date = String(o["data"]["date"]);
        this.inventory = String(o["data"]["inventory"]);
        this.noPages = String(o["data"]["noPages"]);
        this.bibliographicLevel = String(o["data"]["bibliographicLevel"]);
        this.dewey = String(o["data"]["dewey"]);
        this.publishingCountry = String(o["data"]["publishingCountry"]);
        this.editorPlace = String(o["data"]["editorPlace"]);
        this.curator = String(o["data"]["curator"]);
        this.type = String(o["data"]["type"]);
        this.translation = String(o["data"]["translation"]);
        this.description = String(o["data"]["description"]);
        this.identification = String(o["data"]["identification"]);
        this.isPublic = Boolean(o["data"]["identification"]);
        this.lastEdit = parseInt(o["data"]["identification"]);
        this.created = parseInt(o["data"]["identification"]);
    }
}