"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Booking = void 0;
var Booking = /** @class */ (function () {
    function Booking() {
        this.classN = "Booking";
        this.version = "1.0.0";
    }
    Booking.prototype.load = function (id) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    Booking.prototype.list = function (q) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    Booking.prototype.create = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    Booking.prototype.save = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    Booking.prototype.delete = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    Booking.prototype.validate = function () {
        if (!this.librarianId || this.bookerId || this.start || !this.end)
            return false;
        if (this.start > this.end || this.gaveBackDate > this.start)
            return false;
        if (!this.u.isStaff)
            return false;
        if (this.lastEdit < this.created)
            return false;
        return true;
    };
    Booking.prototype.toObj = function () {
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
    };
    Booking.prototype.fromObj = function (o) {
        if (o["type"] != this.classN || o["version"] != this.version)
            throw new ObjectMismatch();
        this.id = parseInt(o["id"]);
        this.start = parseInt(o["start"]);
        this.end = parseInt(o["end"]);
        this.gaveBackDate = parseInt(o["gaveBackDate"]);
        this.librarianId = parseInt(o["librarianId"]);
        this.bookerId = parseInt(o["bookerId"]);
        this.privateComment = String(o["privateComment"]);
        this.approved = Boolean(o["approved"]);
    };
    return Booking;
}());
exports.Booking = Booking;
//# sourceMappingURL=Booking.class.js.map