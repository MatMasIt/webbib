import { Api } from "./Api.class";
import { Authentication } from "../interfaces/Authentication.interface";
import { CRUDL } from "../interfaces/CRUDL.interface";
import { Validation } from "../interfaces/Validation.interface";
import { ObjSerialize } from "../interfaces/ObjSerialize.interface";
import { Query } from "./Query.class";
export class User implements Authentication, CRUDL, Validation, ObjSerialize {
    version = "1.0.0";
    id: bigint;
    name: string;
    surname: string;
    email: string;
    birthDate: string;
    created: bigint;
    lastEdit: bigint;
    allowLogin: boolean;
    isStaff: boolean;
    token: string | null;
    api: Api;
    u: User | null;
    constructor(api: Api) {
        this.api = api;
    }
    toObj(): object {
        return {
            "type": "User",
            "version": "1.0.0",
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
        if (o["type"] != "User" || o["version"] != "1.0.0") throw new ObjectMismatch();
        this.id = BigInt(o["id"]);
        this.name = String(o["data"]["name"]);
        this.surname = String(o["data"]["surname"]);
        this.email = String(o["data"]["email"]);
        this.isStaff = Boolean(o["data"]["isStaff"]);
        this.allowLogin = Boolean(o["data"]["allowLogin"]);
        this.birthDate = String(o["data"]["birthDate"]);
        this.token = String(o["data"]["token"]);
        this.lastEdit = BigInt(o["lastEdit"]);
        this.created = BigInt(o["created"]);
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
    load(id: bigint): void {
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
    fromToken(token: string): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.me",
                "sessionToken": this.token
            });
            req.then(function ok(data) {
                this.fromObj(data);
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            }
        }.bind(this));
    }
    login(email: string, password: string): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.login",
                "email": email,
                "password": password
            });
            req.then(function ok(data) {
                this.fromObj(data);
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            }
        }.bind(this));
    }
    logout(): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.logout",
                "sessionToken": this.token
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            }
        }.bind(this));
    }
}