"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.User = void 0;
var User = /** @class */ (function () {
    function User(api) {
        this.version = "1.0.0";
        this.api = api;
    }
    User.prototype.toObj = function () {
        throw new Error("Method not implemented.");
    };
    User.prototype.fromObj = function (o) {
        throw new Error("Method not implemented.");
    };
    User.prototype.validate = function () {
        throw new Error("Method not implemented.");
    };
    User.prototype.load = function (id) {
        throw new Error("Method not implemented.");
    };
    User.prototype.list = function (q) {
        throw new Error("Method not implemented.");
    };
    User.prototype.create = function () {
        throw new Error("Method not implemented.");
    };
    User.prototype.save = function () {
        throw new Error("Method not implemented.");
    };
    User.prototype.delete = function () {
        throw new Error("Method not implemented.");
    };
    User.prototype.fromToken = function (token) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
                "action": "users.me",
                "sessionToken": this.sessionToken
            });
            req.then(function ok(data) {
                this.fromObj(data);
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            };
        }.bind(this));
    };
    User.prototype.login = function (email, password) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
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
            };
        }.bind(this));
    };
    User.prototype.logout = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
                "action": "users.logout",
                "sessionToken": this.sessionToken
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            };
        }.bind(this));
    };
    return User;
}());
exports.User = User;
//# sourceMappingURL=User.class.js.map