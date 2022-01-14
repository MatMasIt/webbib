import { CRUDL } from "../interfaces/CRUDL.interface";
import { ObjSerialize } from "../interfaces/ObjSerialize.interface";
import { Validation } from "../interfaces/Validation.interface";
import { Query } from "./Query.class";
import { User } from "./User.class";

export class Booking implements CRUDL, Validation, ObjSerialize {
    classN = "Booking";
    version = "1.0.0";
    id: number;
    start: number;
    end: number;
    gaveBackDate: number;
    librarianId: number;
    bookerId: number;
    privateComment: string;
    approved: boolean;
    lastEdit: number;
    created: number;
    u: User;
    load(id: number): Promise<boolean> {
        return new Promise<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "bookings.get",
                "sessionToken": this.u.token,
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
                "action": "bookings.list",
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
                "action": "bookings.create",
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
                "action": "bookings.edit",
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
                "action": "bookings.remove",
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
        if (!this.librarianId || this.bookerId || this.start || !this.end) return false;
        if (this.start > this.end || this.gaveBackDate > this.start) return false;
        if (!this.u.isStaff) return false;
        if (this.lastEdit < this.created) return false;
        return true;
    }
    toObj(): object {
        return {
            "type": this.classN,
            "version": this.version,
            "id": this.id,
            "data": {
                "start": this.start,
                "end": this.end,
                "gaveBackDate": this.gaveBackDate,
                "librarianId": this.librarianId,
                "bookerId": this.bookerId,
                "privateComment": this.privateComment,
                "approved": this.approved
            },
            "lastEdit": this.lastEdit,
            "created": this.created
        };
    }
    fromObj(o: object): void {
        if (o["type"] != this.classN || o["version"] != this.version) throw new ObjectMismatch();
        this.id = parseInt(o["id"]);
        this.start = parseInt(o["start"]);
        this.end = parseInt(o["end"]);
        this.gaveBackDate = parseInt(o["gaveBackDate"]);
        this.librarianId = parseInt(o["librarianId"]);
        this.bookerId = parseInt(o["bookerId"]);
        this.privateComment = String(o["privateComment"]);
        this.approved = Boolean(o["approved"]);
    }
}