"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.User = void 0;
var UnfitUserObject_exception_1 = require("../exceptions/UnfitUserObject.exception");
var User = /** @class */ (function () {
    function User(api) {
        this.classN = "User";
        this.version = "1.0.0";
        this.api = api;
    }
    User.prototype.saveToken = function () {
        if (this.u != null)
            throw new UnfitUserObject_exception_1.UnfitUserObject();
        localStorage.setItem("token", this.token);
        return true;
    };
    User.prototype.loadToken = function () {
        if (this.u != null)
            throw new UnfitUserObject_exception_1.UnfitUserObject();
        this.token = localStorage.getItem("token");
        return true;
    };
    User.prototype.clearToken = function () {
        if (this.u != null)
            throw new UnfitUserObject_exception_1.UnfitUserObject();
        localStorage.removeItem("token");
        this.token = "";
        return true;
    };
    User.prototype.toObj = function () {
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
    };
    User.prototype.fromObj = function (o) {
        if (o["type"] != this.classN || o["version"] != this.version)
            throw new ObjectMismatch();
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
    };
    User.prototype.validate = function () {
        var validateEmail = function (email) {
            return String(email)
                .toLowerCase()
                .match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
        };
        if (!this.email.length || !this.name.length || !this.surname.length || !validateEmail(this.email))
            return false;
        if (this.lastEdit < this.created)
            return false;
        return true;
    };
    User.prototype.load = function (id) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    User.prototype.list = function (q) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    User.prototype.create = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    User.prototype.save = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    User.prototype.delete = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    User.prototype.fromToken = function (token) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    User.prototype.login = function (email, password) {
        if (this.u != null)
            throw new UnfitUserObject_exception_1.UnfitUserObject();
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    User.prototype.logout = function () {
        if (this.u != null)
            throw new UnfitUserObject_exception_1.UnfitUserObject();
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
    };
    return User;
}());
exports.User = User;
//# sourceMappingURL=User.class.js.map