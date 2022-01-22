import { Api } from "./Api.class";
import { Authentication } from "../interfaces/Authentication.interface";
import { CRUDL } from "../interfaces/CRUDL.interface";
import { Validation } from "../interfaces/Validation.interface";
import { ObjSerialize } from "../interfaces/ObjSerialize.interface";
import { Query } from "./Query.class";
import { TokenStorage } from "../interfaces/TokenStorage.interface";
import { UnfitUserObject } from "../exceptions/UnfitUserObject.exception";
export class User implements Authentication, CRUDL, Validation, ObjSerialize, TokenStorage {
    classN = "User";
    version = "1.0.0";
    id: number;
    name: string;
    surname: string;
    email: string;
    birthDate: string;
    created: number;
    lastEdit: number;
    allowLogin: boolean;
    isStaff: boolean;
    token: string | null;
    api: Api;
    u: User | null;
    constructor(api: Api) {
        this.api = api;
    }
    saveToken(): boolean {
        if (this.u != null) throw new UnfitUserObject();
        localStorage.setItem("token", this.token);
        return true;
    }
    loadToken(): boolean {
        if (this.u != null) throw new UnfitUserObject();
        this.token = localStorage.getItem("token");
        return true;
    }
    clearToken(): boolean {
        if (this.u != null) throw new UnfitUserObject();
        localStorage.removeItem("token");
        this.token = "";
        return true;
    }
    toObj(): object {
        return {
            "type": this.classN,
            "version": this.version,
            "id": this.id,
            "data": {
                "name": this.name,
                "surname": this.surname,
                "email": this.email,
                "isStaff": this.isStaff,
                "allowLogin": this.allowLogin,
                "birthDate": this.birthDate,
            },
            "lastEdit": this.lastEdit,
            "created": this.created
        };
    }
    fromObj(o: object): void {
        if (o["type"] != this.classN || o["version"] != this.version) throw new ObjectMismatch();
        this.id = parseInt(o["id"]);
        this.name = String(o["data"]["name"]);
        this.surname = String(o["data"]["surname"]);
        this.email = String(o["data"]["email"]);
        this.isStaff = Boolean(o["data"]["isStaff"]);
        this.allowLogin = Boolean(o["data"]["allowLogin"]);
        this.birthDate = String(o["data"]["birthDate"]);
        this.token = String(o["data"]["token"]);
        this.lastEdit = parseInt(o["lastEdit"]);
        this.created = parseInt(o["created"]);
    }
    validate(): boolean {
        let validateEmail = (email) => {
            return String(email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        };

        if (!this.email.length || !this.name.length || !this.surname.length || !validateEmail(this.email)) return false;
        if (this.lastEdit < this.created) return false;
        return true;
    }
    load(id: number): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.get",
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
                "sessionToken": this.u.token ? this.u.token : this.token,
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
                "action": "users.create",
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
                "action": "users.edit",
                "sessionToken": this.u.token ? this.u.token : this.token,
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
                "sessionToken": this.u.token ? this.u.token : this.token,
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
    fromToken(token: string): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.me",
                "sessionToken": token
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
    login(email: string, password: string): Promise<boolean> {
        if (this.u != null) throw new UnfitUserObject();
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.login",
                "email": email,
                "password": password
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
    logout(): Promise<boolean> {
        if (this.u != null) throw new UnfitUserObject();
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.logout",
                "sessionToken": this.u.token ? this.u.token : this.token
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    }
}